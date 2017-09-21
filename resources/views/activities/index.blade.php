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


                 <div class="row mb-3">
                <div class="col-sm-12">
                     {{ Form::open(array('url' => '/generateActivities', 'method' => 'get')) }}
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


