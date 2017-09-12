@extends('layouts.app')

@section('content')

   <div class="card mx-auto">
        <div class="card-header">
        Handler's Mapping

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/handlers/create') }}">
        Add New Mapping
        </a>
        </div>
        <div class="card-body">


            <handlers></handlers>


        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection
