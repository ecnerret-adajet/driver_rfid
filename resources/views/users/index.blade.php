@extends('layouts.app')

@section('content')

 <div class="card mx-auto mb-3">
        <div class="card-header">
        All Users

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/users/create') }}">
        Add New User
        </a>
        </div>
        <div class="card-body">
            <users></users>
        </div><!-- end card-body -->
    </div> <!-- end card -->

@endsection


