@extends('layouts.app')
@section('content')

   <div class="card mx-auto mb-3">
        <div class="card-header">
        Entries Monitoring
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





@endsection