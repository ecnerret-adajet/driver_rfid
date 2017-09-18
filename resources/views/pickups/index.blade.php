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

        </div><!-- end card-body -->
    </div> <!-- end card -->

@endsection



