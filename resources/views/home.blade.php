@extends('layouts.app')
@section('content')

  <!-- Breadcrumbs -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">My Dashboard</li>
    </ol>

    <home></home>

    @if(\Entrust::hasRole('Administrator') || \Entrust::hasRole('Monitoring') || \Entrust::hasRole('Approver') || \Entrust::hasRole('Queue-monitoring'))

        {{--  <dashboard></dashboard>  --}}

         <div class="card mx-auto mb-3">
        <div class="card-header">
        Entries Monitoring

        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exportEntries">
            Export Entries
        </button>
        </div>
        <div class="card-body">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#gate" role="tab">Gate Entries</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#queues" role="tab">Queues Entries</a>
            </li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            <div class="tab-pane active" id="gate" role="tabpanel">
                <gate></gate>
            </div>
        
            <div class="tab-pane" id="queues" role="tabpanel">
               <driverqueue></driverqueue>
            </div>

        </div>

             
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


    <!-- Modal -->
    <div class="modal fade" id="exportEntries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Entries Report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <export-entries></export-entries>
        </div>
        </div>
    </div>
</div>

    @else

        <div class="row mt-4">
            <div class="col p-3 text-center">
                <p class="display-4 text-muted">
                    We Cannot Show the dashboard
                </p>
            </div>
        </div>
  
    @endif
  
@endsection

