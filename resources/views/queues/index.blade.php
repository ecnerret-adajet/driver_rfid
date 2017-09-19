@extends('layouts.app')

@section('content')



   <div class="card mx-auto mb-3">
        <div class="card-header">
        All Queues

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <queue></queue>

            <div class="row">
                <div class="col-sm-12">

                    <div class="table-responsive">
                        <table class="table" width="100%" id="dataTable" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Driver Name</th>
                                    <th>Plate Number</th>
                                    <th>Hauler</th>
                                    <th>Date/Time</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log_queues as $queue)
                                    @if(count($queue->drivers) != 0)
                                    <tr>
                                    @foreach($queue->drivers as $driver)
                                            <td>
                                                <img class="rounded-circle" style="height: 60px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="top">
                                                {{ $driver->name }} {{ $queue->LogID }}
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
                                            {{ date('m/d/y h:i:s A',strtotime($queue->LocalTime)) }}
                                            </td>
                                            <td>

                                              @forelse($queues->where('LogID',$queue->LogID) as $x)
                                               
                                                <a class="btn btn-secondary btn-sm disabled" href="javascript:void(0);">
                                                    Done
                                                </a>
                                             @empty
                                                 <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#queueModal-{{$queue->LogID}}" href="javascript:void(0);">
                                                    Mark as done
                                                </a>
                                             @endforelse


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

    @foreach($log_queues as $queue)
     <!-- Logout Modal -->
        <div class="modal fade" id="queueModal-{{$queue->LogID}}" tabindex="-1" role="dialog" aria-labelledby="queueModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="queueter">
            <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="queueModalLabel">Queue Turn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            
    
            </div>
            <div class="modal-body">

                                      
               <em>Are you sure you want to proceed with this action?</em>
            

            </div>
            <div class="modal-footer">    
            <a class="btn btn-primary" href="javascript::void(0);" onclick="event.preventDefault();document.getElementById('queue-form').submit();">Save changes</a>
            <form id="queue-form" action="{{url('/queues/'.$queue->LogID)}}" method="POST" style="display: none;">
                {{ csrf_field() }}
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


