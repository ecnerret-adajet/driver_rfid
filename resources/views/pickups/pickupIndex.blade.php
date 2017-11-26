@extends('layouts.online')
@section('content')


   <div class="card mx-auto mb-3">
        <div class="card-header">
            One Time Vendor
            
            <a class="btn btn-danger btn-sm pull-right bootstrap-modal-form-open" href="javascript:void(0);" data-toggle="modal" data-target="#addPickup">
                Add New Pickup
            </a>

        </div>
        <div class="card-body">


            <pickup-online></pickup-online>

            
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


     <!-- Add New Pickup Modal -->
    <div class="modal fade" id="addPickup" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="queueter">
        <div class="modal-content">
        <div class="modal-header">

            <h6 class="modal-title" id="driverModalLabel">Add New Pickup</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        

        </div>
        {!! Form::model($pickup = new \App\Pickup, ['url' => 'storePickup', 'id' => 'pickupForm', 'class' => 'bootstrap-modal-form', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
        <div class="modal-body">
            
          {!! csrf_field() !!}
            @include('pickups.pickupForm')
                           

          


        </div>
        <div class="modal-footer">  
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                 <button type="submit" class="btn btn-primary">Confirm</button> 
        </div>
        {!! Form::close() !!} 
            
        </div>
    </div>
    </div><!-- end modal -->


@endsection