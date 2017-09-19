@extends('layouts.app')

@section('content')


   <div class="card mx-auto mb-3">
        <div class="card-header">
        System Logs

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        {{--  <a class="btn btn-primary btn-sm pull-right mr-2" href="#">
        Export Logs
        </a>  --}}
        </div>
        <div class="card-body">

         <div class="table-responsive">
              <table class="table table-bordered" width="100%" id="dataTable" cellspacing="0">
                <thead>
                  <tr>
                    <th>Date Time</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>Module</th>
                    <th>Subject ID</th>
                  </tr>
                </thead>
               <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                        <td>
                            @foreach($users->where('id',$activity->causer_id) as $user)
                                    {{ $user->name }} 
                            @endforeach
                        </td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->subject_type }}</td>
                        <td>{{ $activity->subject_id }}</td>
                        {{--  <td>{{ $activity->properties }}</td>  --}}
                    
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


