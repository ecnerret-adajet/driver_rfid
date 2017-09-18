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
        
        
        
        </div><!-- end card-body -->
    </div> <!-- end card -->




@endsection


