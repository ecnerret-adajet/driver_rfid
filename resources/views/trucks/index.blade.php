@extends('layouts.app')

@section('content')

   <div class="card mx-auto mb-3">
        <div class="card-header">
             All Trucks

            <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
            </a>

            @role((['Administrator','Monitoring']))
                <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('trucks/create') }}">
                    Add New Truck
                </a>
            @endrole

        </div>
        <div class="card-body">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#active_trucks" role="tab">Active Trucks</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#no_driver" role="tab">No Driver Assigned</a>
                    </li>

                     <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#deactivated_trucks" role="tab">Deactivated Trucks</a>
                    </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="active_trucks" role="tabpanel">
                    <trucks user_role="{{ Auth::user()->roles->first()->name }}"></trucks>
                </div>
            
                <div class="tab-pane pt-3" id="no_driver" role="tabpanel">
                    <no-driver user_role="{{ Auth::user()->roles->first()->name }}"></no-driver>
                </div>

                  <div class="tab-pane pt-3" id="deactivated_trucks" role="tabpanel">
                    <deactivated-trucks user_role="{{ Auth::user()->roles->first()->name }}"></deactivated-trucks>
                </div>

                

            </div>

             
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection
@section('script')
    <script>
        $("[data-mask]").inputmask();
    </script>
@endsection



