@extends('layouts.app')

@section('content')
@inject('drfp', 'App\Http\Controllers\LineupsController') 



   <div class="card mx-auto mb-3">
        <div class="card-header">
        All Queues

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <lineup></lineup>

            
                <div class="row mb-3">
                <div class="col-sm-12">
                     {{ Form::open(array('url' => '/generateLineups', 'method' => 'get')) }}
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

                    <div class="table-responsive">
                        <table class="table" width="100%" id="dataTable" cellspacing="0" style="font-size: 70%">
                            <thead>
                                <tr style="text-transform:uppercase">
                                    <th></th>
                                    <th>Driver Name</th>
                                    <th>Plate Number</th>
                                    <th>Hauler</th>
                                    <th>Date/Time</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log_lineups as $lineup)
                                    @if(count($lineup->drivers) != 0)
                                    <tr>
                                    @foreach($lineup->drivers as $driver)
                                            <td>
                                                <img class="rounded-circle" style="height: 60px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="top">
                                            </td>
                                            <td>
                                                {{ $driver->name }} 
                                            </td>
                                            <td>
                                                @foreach($driver->trucks as $truck)
                                                    {{ $truck->plate_number }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($driver->haulers as $hauler)
                                                    {{ $hauler->name }}
                                                @endforeach
                                            </td>
                                    @endforeach
                                            <td>
                                            {{ date('m/d/y h:i:s A',strtotime($lineup->LocalTime)) }}
                                            </td>

                                            <td>

                                            @foreach($driver->trucks as $truck)

                                                @if( $drf->checkLastTrip($truck->plate_number) === 'received' )
                                                                                  
                                                @forelse($lineups->where('LogID',$lineup->LogID) as $x)
                                                    <a class="btn btn-secondary btn-sm disabled" href="javascript:void(0);">
                                                        On Hustling
                                                    </a>
                                                @empty
                                                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#lineupModal-{{$lineup->LogID}}" href="javascript:void(0);">
                                                        Hustling
                                                    </a>
                                                @endforelse


                                                @else

                                                    NO STATUS

                                                @endif

                                            @endforeach


                                            </td>
                                     </tr>
                                     @endif
                                @endforeach
                            </tbody>

                        </table>
                    </div>


                </div>
            </div>
        
        
        
        </div><!-- end card-body -->
    </div> <!-- end card -->

    @foreach($log_lineups as $lineup)
     <!-- Logout Modal -->
        <div class="modal fade" id="lineupModal-{{$lineup->LogID}}" tabindex="-1" role="dialog" aria-labelledby="lineupModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="lineupter">
            <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="lineupModalLabel">Assign To Hustling</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            
    
            </div>
            <div class="modal-body">

                                      
               <em>Are you sure you want to proceed with this action?</em>
            

            </div>
            <div class="modal-footer">                
            <form id="lineup-form" method="POST" action="{{url('/lineups/'.$lineup->LogID)}}">
                {{ csrf_field() }}
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


