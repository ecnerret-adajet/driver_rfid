@extends('layouts.app')

@section('content')

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>




   <div class="card mx-auto">
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


