@extends('layouts.app')

@section('content')


   <div class="card mx-auto mb-3">
        <div class="card-header">
        All Drivers

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="#">
        Export Logs
        </a>
        </div>
        <div class="card-body">


        <div class="row">
        <div class="col-sm-12">
            <div v-if="!loading">
                <ul class="list-group">
                @foreach($activities as $activity)
                    <li class="list-group-item">
                        <div class="row">   
                            <div class="col-sm-1">

                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-user-o fa-stack-1x fa-inverse"></i>
                                    </span>

                            </div>
                            <div class="col-sm-8">
                                
                                    @foreach($users->where('id',$activity->causer_id) as $user)
                                            {{ $user->name }} has {{ $activity->description }} a {{ $activity->subject_type }}
                                    @endforeach

                            </div>
                            <div class="col-sm-3 pull-right right">

                                    <span class="badge badge-primary pull-right">
                                        {{ date('Y-m-d h:i:s A', strtotime($activity->created_at)) }}
                                    </span>
                                
                            </div>
                        </div>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->



@endsection


