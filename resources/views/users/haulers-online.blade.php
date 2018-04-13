@extends('layouts.online')
@section('content')



   <div class="card mx-auto mb-3">
        <div class="card-header">
        Hauler's Managed
        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

        <!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">All Drivers</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">All Trucks</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="home" role="tabpanel">
    <hauleronline user="{{ Auth::user()->id }}"></hauleronline>
  </div>
  <div class="tab-pane" id="profile" role="tabpanel">
    <hauleronlinetruck user="{{ Auth::user()->id }}"></hauleronlinetruck>
  </div>
</div>

            
        
        </div><!-- end card-body -->
    </div> <!-- end card -->




@endsection


