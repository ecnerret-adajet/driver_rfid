@extends('layouts.app')

@section('content')



   <div class="card mx-auto mb-3">
        <div class="card-header">
        All Drivers

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/drivers/create') }}">
        Add New Driver
        </a>
        </div>
        <div class="card-body">
            <drivers></drivers>
        </div><!-- end card-body -->
    </div> <!-- end card -->




@endsection


