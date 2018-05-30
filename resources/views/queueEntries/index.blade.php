@extends('layouts.app')
@section('content')

   <div class="card mx-auto mb-3">
        <div class="card-header">
        Queues Monitoring

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