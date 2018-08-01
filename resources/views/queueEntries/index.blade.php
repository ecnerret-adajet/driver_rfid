@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2">
          <div class="col-6">
            <h1 class="m-0 text-dark">Queue Entries</h1>
            <span class="text-muted">All Pickup and Deliveries logs</span>
          </div><!-- /.col -->
          <div class="col-6">
            <ol class="breadcrumb float-right">
            <li>
              <a  href="{{ url('/monitor/feed') }}">
                  Visit Previous Version
                </a>
            </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
    </div>


        <!-- Nav tabs -->
<ul class="nav nav-tabs mt-5" role="tablist">
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
<div class="tab-content mb-3">

  @role((['Administrator','Queue-monitoring','Pickup-level-2']))
    <div class="tab-pane active" id="home" role="tabpanel">
      <monitor-queue-pickups></monitor-queue-pickups>
    </div>
  @endrole

  @role((['Administrator','Queue-monitoring']))
    @foreach($driverqueues as $driverqueue)
    <div class="tab-pane" id="queue-{{ $driverqueue->id }}" role="tabpanel">
      <queue-parent location="{{ $driverqueue->id }}"></queue-parent>
    </div>
    @endforeach
  @endrole

</div>








@endsection
