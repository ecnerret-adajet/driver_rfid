@extends('layouts.app')

@section('content')


   <div class="card mx-auto">
        <div class="card-header">
        Driver for approval

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="table-responsive">
            <table class="table" width="100%" id="dataTable" cellspacing="0">
            <thead>
                <tr>
                <th>Driver Name</th>
                <th>Date</th>
                <th>Classification</th>
                <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($print as $driver)
                <tr>
                <td>{{ $driver->name }}</td>
                <td>{{ date('F m, Y', strtotime($driver->created_at)) }}</td>
                <td>
                    @if(count($driver->confirms) == 0)
                        Pending
                    @else    
                        @foreach($driver->confirms->reverse()->take(1) as $confirm)
                                {{ $confirm->classification }}
                        @endforeach
                    @endif
                </td>
                <td>
                @forelse($driver->confirms->reverse()->take(1) as $confirm)
                        @if($confirm->status ==  "Approve")
                        
                            @if($driver->availability == 0 || $driver->print_staus == 1 && $driver->notif_status == 0)
                                 @if(Entrust::hasRole('Approver') || Entrust::hasRole('Administrator'))
                                    @foreach($driver->trucks as $truck)
                                        <a href="{{ url('/confirm/create/'.$driver->id.'/'.$truck->id) }}" class="btn btn-primary btn-sm">
                                            CONFIRM NOW
                                        </a>
                                    @endforeach
                                @else
                                    <span class="badge badge-primary">
                                        PENDING APPROVAL
                                    </span>
                                @endif
                            @else
                                <span class="badge badge-success">
                                    APPROVED
                                </span>
                            @endif

                        @else
                            <span class="badge badge-danger">
                                DISAPPROVED
                            </span>
                        @endif
                    @empty

                    @if(Entrust::hasRole('Approver') || Entrust::hasRole('Administrator'))
                        @foreach($driver->trucks as $truck)
                            <a href="{{ url('/confirm/create/'.$driver->id.'/'.$truck->id) }}" class="btn btn-primary btn-sm">
                                CONFIRM NOW
                            </a>
                        @endforeach
                    @else
                        <span class="badge badge-primary">
                             PENDING APPROVAL
                        </span>
                    @endif

                    @endforelse
                </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable();
  });
</script>
@endsection


