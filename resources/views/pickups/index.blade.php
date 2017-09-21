@extends('layouts.app')

@section('content')


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
                                        {!! Form::input('date','start_date', null, ['class' => 'form-control']) !!}
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
                                        {!! Form::input('date', 'end_date', null, ['class' => 'form-control', 'max' => ''.date('Y-m-d', strtotime(Carbon\Carbon::now())).'' ]) !!} 
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
                                    {{--  <th>Age</th>  --}}
                                    <th>Pickup #</th>
                                    <th>Plate #</th>
                                    <th>Driver Name</th>
                                    <th>Company</th>
                                    <th>TRUCKSCALE IN</th>
                                    <th>TRUCKSCALE OUT</th>
                                    <th>BETWEEN</th>
                                    {{--  <th>Status</th>  --}}
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pickups as $pick)
                                <tr class="{{ $pick->availability == 1 ? 'table-danger' : 'table-success' }}">
                                    {{--  <td>
                                            {{ $pick->created_at->diffForHumans() }}      
                                    </td>  --}}
                                    <td>
                                        {{$pick->cardholder->Name}}
                                    </td>
                                    <td>
                                        {{$pick->plate_number}}
                                    </td>
                                    <td>
                                        {{$pick->driver_name}}
                                    </td>
                                    <td>
                                        {{$pick->company}}
                                    </td>


                                    <td>

                                        @forelse(App\Log::pickupIn($pick->cardholder_id, $pick->created_at)->get() as $logIn)
                                            {{ $pick_in = date('m/d/y h:i:s A',strtotime($logIn->LocalTime))}}<br/>
                                        @empty
                                            NO IN
                                        @endforelse

                                    </td>
                                    <td>
                                        @forelse(App\Log::pickupOut($pick->cardholder_id, $pick->created_at)->get() as $logOut)

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
                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse(App\Log::pickupOut($pick->cardholder_id, $pick->created_at)->get() as $logOut)

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
                                        @endforelse

                                    </td>
                                    {{--  <td>
                                        @if($pick->availability == 1)
                                            <span class="inTransit">
                                                <i class="ion ion-record"></i>
                                            </span>
                                        @else
                                            <span class="inPlant">
                                                <i class="ion ion-record"></i>
                                            </span>
                                        @endif
                                    </td>  --}}
                                    <td>
                                        {{--  <div class="dropdown pull-right">
                                            <a href="#" class="btn btn-action btn-sm btn-primary" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                                <ul class="dropdown-menu">
                                                <li><a href="{{url('/pickups/'.$pick->id.'/edit')}}"> <span>Edit Entry</span> </a></li>
                                                @if($pick->availability == 1) 
                                                <li><a data-toggle="modal" data-target=".bs-setInactive{{$pick->id}}-modal-lg" href=""> <span>Deactive RFID</span> </a></li>
                                                @endif
                                                </ul>   
                                        </div>  --}}

                                        <div class="btn-group">
                                            <a class="btn btn-primary btn-sm" href="{{url('/pickups/'.$pick->id.'/edit')}}">Edit</a>
                                             @if($pick->availability == 1) 
                                            <a data-toggle="modal" class="btn btn-secondary btn-sm" data-target=".bs-setInactive{{$pick->id}}-modal-lg" href="">
                                                Deactivate
                                            </a>
                                             @endif
                                        </div>

                               
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
  @endforeach

@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection




