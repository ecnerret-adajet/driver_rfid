@extends('layouts.app')

@section('top-script')
    <style>
        .help-block {
            color: red;
            margin-left: 150px;
        }
    </style>
@endsection

@section('content')
@inject('search', 'App\Http\Controllers\PickupsController')


   <div class="card mx-auto mb-3">
        <div class="card-header">
        All Pickups

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/pickups/create') }}">
        Add New Pickup
        </a>
        </div>
        <div class="card-body">


           <pickups></pickups>

                <div class="row mb-3">
                <div class="col-sm-12">
                     {{ Form::open(array('url' => '/generatePickups', 'method' => 'get')) }}
                        <form>

                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('start_date') ? ' has-danger' : '' }}">
                                        <label>Start Date</label>
                                        {!! Form::input('date','start_date', Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control']) !!}
                                        @if ($errors->has('start_date'))
                                            <div class="form-control-feedback">
                                            <small>
                                                {{ $errors->first('start_date') }}
                                                </small>
                                            </div>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('end_date') ? ' has-danger' : '' }}">
                                        <label>End Date</label>
                                        {!! Form::input('date', 'end_date', Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'max' => ''.date('Y-m-d', strtotime(Carbon\Carbon::now())).'' ]) !!} 
                                        @if ($errors->has('end_date'))
                                            <div class="form-control-feedback">
                                            <small>
                                                {{ $errors->first('end_date') }}
                                                </small>
                                            </div>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit"  class="btn btn-secondary  btn-block">Generate</button>
                            </div>
                        </div>

                        
                        </form>
                    {!! Form::close() !!} 
                </div>             
             </div>

           <div class="row">
                <div class="col-sm-12">
                    <div class="table-response">
                        <table class="table" width="100%" id="dataTable" cellspacing="0" style="font-size:70%">
                            <thead>
                                <tr style="text-transform: uppercase">
                                    <th>Pickup #</th>
                                    <th>Driver Details</th>
                                    <th>Created Date</th>
                                    <th>Checkout Date</th>
                                    <th>TRUCKSCALE IN</th>
                                    <th>TRUCKSCALE OUT</th>
                                    <th>BETWEEN</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pickups as $pick)
                                <tr class="{{ $pick->availability == 1 ? 'table-danger' : 'table-success' }}">
                                    <td>
                                        {{ !empty($pick->cardholder->Name) ? $pick->cardholder->Name : 'UNPROCESS' }} <br/>
                                    </td>
                                    <td>
                                        {{$pick->driver_name}}<br/>
                                        {{$pick->plate_number}}<br/>
                                        {{$pick->company}}
                                    </td>
                               
                                    <td>

                                    {{ date('m/d/y h:i:s A', strtotime($pick->created_at))}}
                                  

                                    </td>
                                    <td>
                                      {{ date('m/d/y h:i:s A', strtotime($pick->updated_at))}}
                                    </td>

                                    <td>

                                        {{--  @forelse(App\Log::pickupIn($pick->cardholder_id, $pick->created_at)->get() as $logIn)
                                            {{ $pick_in = date('m/d/y h:i:s A',strtotime($logIn->LocalTime))}}<br/>
                                        @empty
                                            NO IN
                                        @endforelse  --}}
                                     

                                        @foreach($search->getTruckscaleIn($pick->cardholder_id, $pick->created_at) as $pick_in )
                                            {{ $pickin = date('m/d/y h:i:s A',strtotime($pick_in->LocalTime))}}<br/>
                                        @endforeach

                                    </td>
                                    <td>
                                        {{--  @forelse(App\Log::pickupOut($pick->cardholder_id, $pick->created_at)->get() as $logOut)

                                        @forelse(App\Log::pickupIn($pick->cardholder_id, $pick->created_at)->get() as $logIn)

                                            @if($logOut->LocalTime > $logIn->LocalTime)
                                            {{ $pick_out = date('m/d/y h:i:s A',strtotime($logOut->LocalTime)) }}
                                            @else
                                                NO OUT
                                            @endif

                                        @empty
                                                CANNOT DETERMINE
                                        @endforelse
                                        @empty
                                                NO OUT
                                        @endforelse  --}}

                                        @foreach($search->getTruckscaleOut($pick->cardholder_id, $pick->created_at) as $pick_in )
                                            {{ $pickout = date('m/d/y h:i:s A',strtotime($pick_in->LocalTime))}}<br/>
                                        @endforeach

                                        

                                    </td>
                                    <td>
                                        {{--  @forelse(App\Log::pickupOut($pick->cardholder_id, $pick->created_at)->get() as $logOut)

                                        @forelse(App\Log::pickupIn($pick->cardholder_id, $pick->created_at)->get() as $logIn)

                                        @if($logOut->LocalTime > $logIn->LocalTime)
                                        {{  $logIn->LocalTime->diffInHours($logOut->LocalTime)}} Hour(s)
                                        @else
                                            N/A
                                        @endif

                                        @empty
                                                N/A
                                        @endforelse
                                        @empty
                                                N/A
                                        @endforelse  --}}

                                         @foreach($search->getTruckscaleIn($pick->cardholder_id, $pick->created_at) as $pick_in )
                                            @foreach($search->getTruckscaleOut($pick->cardholder_id, $pick->created_at) as $pick_out )
                                             {{  $pick_in->LocalTime->diffInHours($pick_out->LocalTime)}} Hour(s)
                                            @endforeach
                                        @endforeach

                                    </td>
                                    <td>    
                                    @if($pick->availability == 1)                          
                                         <a class="dropdown pull-right btn btn-outline-secondary" href="#" id="pickupDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pickupDropdown">
                                            @if(empty($pick->cardholder->Name))
                                            <a class="dropdown-item bootstrap-modal-form-open" data-toggle="modal" data-target=".assign-rfid-{{$pick->id}}" style="width: 70%;" href="javascript:void(0);">
                                                <small>
                                                Assign RFID
                                                </small>
                                             </a>
                                             @endif
                                             <a class="dropdown-item" style="width: 70%;" href="{{url('/pickups/'.$pick->id.'/edit')}}">
                                                <small>
                                                Edit Entry
                                                </small>
                                             </a>
                                             @if($pick->availability == 1) 
                                            <a data-toggle="modal" class="dropdown-item"  style="width: 70%;" data-target=".bs-setInactive{{$pick->id}}-modal-lg" href="">
                                              <small>  Deactivate </small>
                                            </a>
                                            @endif
                                        </div><!-- end dropdown --> 
                                    @endif                              
                                    </td>
                                </tr>
                                @endforeach                          
                            </tbody>
                        </table>
                    </div>
                </div>
           </div>

        </div><!-- end card-body -->
    </div> <!-- end card -->


      @foreach($pickups as $pick)
        <!-- Change availabitlity status to inactive -->
        <div class="modal fade bs-setInactive{{$pick->id}}-modal-lg">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Deactivate</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>  
                    Are you sure you want to proceed with this action?
                </p>
            </div>
            <div class="modal-footer">
                 <form method="POST" action="{{ url('/pickups/deactivate/'.$pick->id) }}">
                            {!! csrf_field() !!}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button> 
                </form>  
            </div>
            </div>
        </div>
        </div>

        <!-- Assign RFID to pickup modal -->
        <div class="modal fade assign-rfid-{{$pick->id}}">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Assign RFID</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::model($pick, ['method' => 'PATCH','route' => ['pickups-assign.update', $pick->id], 'class' => 'bootstrap-modal-form', 'enctype'=>'multipart/form-data']) !!}
            <div class="modal-body">
             {!! csrf_field() !!}

             <div class="row">
                <div class="col">
                    <small class="text-muted" style="text-transform:uppercase">
                        Driver Name:
                    </small><br/>
                    {{ $pick->driver_name }}
                </div>
                <div class="col">
                    <small class="text-muted" style="text-transform:uppercase">
                        Plate Number:
                    </small><br/>
                    {{ $pick->plate_number }}
                </div>
                <div class="col">
                    <small class="text-muted" style="text-transform:uppercase">
                        Company:
                    </small><br/>
                    {{ $pick->company }}
                </div>
             </div>

             <hr/>

                    <div class="form-group row {{ $errors->has('cardholder_list') ? ' has-danger' : '' }}">
                            <label class="col-md-2">RFID Card</label>
                            <div class="col-md-10">
                            {!! Form::select('cardholder_list', $search->cardholderAvailability(), null, ['placeholder' => 'Select Pickup Number', 'class' => 'form-control select2-pickup'] ) !!}
                            @if ($errors->has('cardholder_list'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('cardholder_list') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button> 
            </div>
            {!! Form::close() !!} 
            </div>
        </div>
        </div>
  @endforeach

@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection




