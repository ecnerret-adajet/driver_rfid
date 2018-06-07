@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2">
          <div class="col-6">
            <h1 class="m-0 text-dark">Queue Entries</h1>
          </div><!-- /.col -->
          <div class="col-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">{{ Carbon\Carbon::today()->format('M d, Y') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
    </div>

   <div class="card mx-auto mb-3 mt-3">
        <div class="card-header">
       Pickup & Deliveries Queues

        <a class="btn btn-sm btn-outline-primary float-right" href="{{ url('/monitor/feed') }}">
          Visit Previous Version
        </a>
        </div>
        <div class="card-body">

        <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  @role((['Administrator','Queue-monitoring','Pickup-level-2']))
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Pickup Queues</a>
    </li>
  @endrole

  @role((['Administrator','Queue-monitoring']))
  @foreach($driverqueues as $driverqueue)
    <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#queue-{{ $driverqueue->id }}" role="tab">{{ $driverqueue->title }}</a>
    </li>
  @endforeach
  @endrole



</ul>

<!-- Tab panes -->
<div class="tab-content">

  @role((['Administrator','Queue-monitoring','Pickup-level-2']))
    <div class="tab-pane active" id="home" role="tabpanel">
      <monitor-queue-pickups></monitor-queue-pickups>
    </div>
  @endrole
  
  @role((['Administrator','Queue-monitoring']))
    @foreach($driverqueues as $driverqueue)
    <div class="tab-pane" id="queue-{{ $driverqueue->id }}" role="tabpanel">
    <queue-parent driverqueue="{{ $driverqueue->id }}"></queue-parent>
    </div>
    @endforeach
  @endrole

</div>

        
        </div><!-- end card-body -->
    </div> <!-- end card -->





@endsection