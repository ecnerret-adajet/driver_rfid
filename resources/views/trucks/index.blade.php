@extends('layouts.app')

@section('content')



   <div class="card mx-auto">
        <div class="card-header">
        All Trucks

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
         <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('trucks/create') }}">
        Add New Truck
        </a>
        </div>
        <div class="card-body">
         @foreach(Auth::user()->roles as $role)
                <trucks role="{{ $role->name }}"></trucks>
         @endforeach
        </div><!-- end card-body -->
    </div> <!-- end card -->



@endsection



