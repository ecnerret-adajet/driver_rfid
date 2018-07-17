@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header mt-3">
        <div class="row mb-2">
          <div class="col-6">
            <h4 class="m-0 text-dark"><span class="text-muted">Master Data</span> > Queue Entries</h4>
          </div><!-- /.col -->
          <div class="col-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">{{ Carbon\Carbon::today()->format('M d, Y') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
    </div>

        <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    @role((['Administrator','Queue-monitoring','Pickup-level-2']))
      <li class="nav-item">
        <a class="nav-link active text-uppercase small" data-toggle="tab" href="#home" role="tab">Pickup Queues</a>
      </li>
    @endrole

    @role((['Administrator','Queue-monitoring']))
    @foreach($driverqueues as $driverqueue)
      <li class="nav-item">
      <a class="nav-link text-uppercase small" data-toggle="tab" href="#queue-{{ $driverqueue->id }}" role="tab">{{ $driverqueue->title }}</a>
      </li>
    @endforeach
    @endrole



  </ul>

  <!-- Tab panes -->
  <div class="tab-content mb-4">

    @role((['Administrator','Queue-monitoring','Pickup-level-2']))
      <div class="tab-pane active" id="home" role="tabpanel">
        <monitor-queue-pickups></monitor-queue-pickups>
      </div>
    @endrole
    
    @role((['Administrator','Queue-monitoring']))
      @foreach($driverqueues as $driverqueue)
      <div class="tab-pane" id="queue-{{ $driverqueue->id }}" role="tabpanel">
      <queue-day-parent driverqueue="{{ $driverqueue->id }}"></queue-day-parent>
      </div>
      @endforeach
    @endrole

  </div>

      
@endsection
