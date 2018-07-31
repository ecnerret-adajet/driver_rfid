@extends('layouts.app')
@section('content')

   <div class="card mx-auto mb-3">
        <div class="card-header">
        Queues Monitoring
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
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#manila" role="tab">MNL(PFMC) Queues</a>
    </li>
  @endrole

    @role((['Administrator','Queue-monitoring']))
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#bataan" role="tab">BTN(MGC) Queues</a>
    </li>
  @endrole

  @role((['Administrator','Queue-monitoring']))
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#lapaz" role="tab">MNL(Lapaz) Queues</a>
    </li>
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
    <div class="tab-pane" id="manila" role="tabpanel">
      <monitor-queue-deliveries></monitor-queue-deliveries>
    </div>
  @endrole



   @role((['Administrator','Queue-monitoring']))
    <div class="tab-pane" id="bataan" role="tabpanel">
      <monitor-queue-deliveries-btn></monitor-queue-deliveries-btn>
    </div>
  @endrole

    @role((['Administrator','Queue-monitoring']))
    <div class="tab-pane" id="lapaz" role="tabpanel">
       <monitor-queue-deliveries-lpz></monitor-queue-deliveries-lpz>
    </div>
  @endrole
</div>



        </div><!-- end card-body -->
    </div> <!-- end card -->





@endsection
