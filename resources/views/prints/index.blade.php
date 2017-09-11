@extends('layouts.app')

@section('content')


   <div class="card mx-auto">
        <div class="card-header">
        RFID Cards for Approval 

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="table-responsive">
            <table class="table" width="100%" id="printTable" cellspacing="0">
            <thead>
                <tr>
                <th>Driver Name</th>
                <th>Clasification</th>
                <th>Created By</th>
                <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($print as $driver)
                <tr>
                <td>{{ $driver->name }}</td>
                <td>
                    @if(count($driver->clasification) > 0)
                     {{ $driver->clasification->name }} 
                    @endif
                </td>
                <td>
                    {{ $driver->user->name }}
                </td>
                <td>
                @forelse($driver->confirms->reverse()->take(1) as $confirm)
                        @if($confirm->status ==  "Approve")
                         {{--  <form method="POST" action="{{ url('/prints',$driver->id) }}">
                         {!! csrf_field() !!}
                        <button  type="submit" class="btn btn-success">APPROVED</button>
                        </form>  --}}
                        <span class="badge badge-success">
                        APPROVED
                        </span>
                        @else
                        <span class="badge badge-danger">
                        DISAPPROVED
                        </span>
                        @endif
                    @empty
                        <span class="badge badge-primary">
                             PENDING APPROVAL
                        </span>
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
    $('#printTable').DataTable();
  });
</script>
@endsection


