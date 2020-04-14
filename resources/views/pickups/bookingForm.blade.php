
@section('top-script')
    <style>
        .help-block {
            color: red;
            margin-left: 230px;
        }
    </style>
@endsection

<div class="form-group row {{ $errors->has('do_number') ? ' has-danger' : '' }}">
     <label class="col-md-3 col-form-label">
        Order Reference 
    </label>
    <div class="col-sm-9 pt-2">

    <label class="radio-inline">
      <input type="radio" name="order_reference" class="mr-2 p-2" value="DO">DO
    </label>
    <label class="radio-inline">
      <input type="radio" name="order_reference" class="mr-2" value="STO">STO
    </label>

       @if ($errors->has('order_reference'))
            <div class="form-control-feedback">
                <small>
                {{ $errors->first('order_reference') }}
                </small>
            </div>
        @endif

    </div>
</div>

<div class="form-group row {{ $errors->has('order_reference_no') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Order Reference No.
    </label>
    <div class="col-md-9">
        {{ Form::text('order_reference_no', null, ['class' => 'form-control', 'placeholder' => 'Enter DO / STO']) }}
    </div>
</div>

   
<div class="form-group row {{ $errors->has('booking_date') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Booking Date
    </label>
    <div class="col-md-9">
        {{ Form::date('booking_date', new \DateTime(), ['class' => 'form-control']) }}
    </div>
</div>

<!-- <div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Shipper's Name
    </label>
    <div class="col-md-9">
        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Shippers Name']) }}
    </div>
</div> -->

<div class="form-group row {{ $errors->has('consignee') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Consignee
    </label>
    <div class="col-md-9">
        {{ Form::text('consignee', null, ['class' => 'form-control', 'placeholder' => 'Enter Consignee Name']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('destination') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Destination
    </label>
    <div class="col-md-9">
        {{ Form::text('destination', null, ['class' => 'form-control', 'placeholder' => 'Enter Destination']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('van_no') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Van No.
    </label>
    <div class="col-md-9">
        {{ Form::text('van_no', null, ['class' => 'form-control', 'placeholder' => 'Enter Van No']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('do_number') ? ' has-danger' : '' }}">
     <label class="col-md-3 col-form-label">
        <!-- COA  -->
    </label>
    <div class="col-sm-9 pt-2">

    <label class="radio-inline">
      <input type="radio" name="ship_type" class="mr-2 p-2" value="Transfer">Transfer
    </label>
    <label class="radio-inline">
      <input type="radio" name="ship_type" class="mr-2" value="Delivery">Delivery
    </label>

       @if ($errors->has('ship_type'))
            <div class="form-control-feedback">
                <small>
                {{ $errors->first('ship_type') }}
                </small>
            </div>
        @endif

    </div>
</div>


<div class="form-group row {{ $errors->has('mode_of_shipment') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Mode of Shipment
    </label>
    <div class="col-md-9">
        {!! Form::select('mode_of_shipment', array('Door to door' => 'Door to door', 'Door to pier' => 'Door to pier','Pier to pier' => 'Pier to pier','Pier to door' => 'Pier to door'), null, ['placeholder' => 'Select Mode of Shipment',  'class' => 'form-control select2-card'] ) !!}
    </div>
</div>

<!-- <div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Driver Name
    </label>
    <div class="col-md-9">
        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Driver Name']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('plate_number') ? ' has-danger' : '' }}">
        <label class="col-md-3 col-form-label">
                Plate Number
        </label>
            <div class="col-md-9">
        {{ Form::text('plate_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Plate Number']) }}
    </div>
</div> -->


