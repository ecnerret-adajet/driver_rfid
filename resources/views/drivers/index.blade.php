@extends('layouts.app')

@section('content')

  <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2">
          <div class="col-6">
            <h1 class="m-0 text-dark">Drivers</h1>
          </div><!-- /.col -->
          <div class="col-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">{{ Carbon\Carbon::today()->format('M d, Y') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
    </div>

    <div class="card mx-auto mb-3">
        <div class="card-header">
               All Drivers

                <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                Back
                </a>
                
                @role((['Administrator','Monitoring']))
                    <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/drivers/create') }}">
                        Add New Driver
                    </a>
                @endrole
        </div>
        <div class="card-body">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#active_drivers" role="tab">Active Drivers</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#no_truck" role="tab">No Truck Assigned</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#deactivated_drivers" role="tab">Deactivated Drivers</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#resigned_drivers" role="tab">Resigned Drivers</a>
                    </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="active_drivers" role="tabpanel">
                    <drivers user_role="{{ Auth::user()->roles->first()->name }}"></drivers>
                </div>
            
                <div class="tab-pane pt-3" id="no_truck" role="tabpanel">
                   <no-truck user_role="{{ Auth::user()->roles->first()->name }}"></no-truck>
                </div>

                <div class="tab-pane pt-3" id="deactivated_drivers" role="tabpanel">
                   <deactivated-drivers user_role="{{ Auth::user()->roles->first()->name }}"></deactivated-drivers>
                </div>

                <div class="tab-pane pt-3" id="resigned_drivers" role="tabpanel">
                   <resigned-drivers user_role="{{ Auth::user()->roles->first()->name }}"></resigned-drivers>
                </div>

            </div>

             
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection


