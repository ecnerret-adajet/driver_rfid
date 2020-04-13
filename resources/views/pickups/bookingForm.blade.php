
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
      <input type="radio" name="coa" class="mr-2 p-2" value="yes">DO
    </label>
    <label class="radio-inline">
      <input type="radio" name="coa" class="mr-2" value="no">STO
    </label>

       @if ($errors->has('coa'))
            <div class="form-control-feedback">
                <small>
                {{ $errors->first('coa') }}
                </small>
            </div>
        @endif

    </div>
</div>

<div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Order Reference No.
    </label>
    <div class="col-md-9">
        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter DO / STO']) }}
    </div>
</div>

   
<div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Booking Date
    </label>
    <div class="col-md-9">
        {{ Form::date('driver_name', new \DateTime(), ['class' => 'form-control']) }}
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

<div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Consignee
    </label>
    <div class="col-md-9">
        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Consignee Name']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Destination
    </label>
    <div class="col-md-9">
        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Destination']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Van No.
    </label>
    <div class="col-md-9">
        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Van No']) }}
    </div>
</div>

<div class="form-group row {{ $errors->has('do_number') ? ' has-danger' : '' }}">
     <label class="col-md-3 col-form-label">
        <!-- COA  -->
    </label>
    <div class="col-sm-9 pt-2">

    <label class="radio-inline">
      <input type="radio" name="coa" class="mr-2 p-2" value="yes">Transfer
    </label>
    <label class="radio-inline">
      <input type="radio" name="coa" class="mr-2" value="no">Delivery
    </label>

       @if ($errors->has('coa'))
            <div class="form-control-feedback">
                <small>
                {{ $errors->first('coa') }}
                </small>
            </div>
        @endif

    </div>
</div>


<div class="form-group row {{ $errors->has('card_list') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
        Mode of Shipment
    </label>
    <div class="col-md-9">
        {!! Form::select('card_list', array('Door to door', 'Door to pier','Pier to pier','Pier to door'), null, ['placeholder' => 'Select Mode of Shipment',  'class' => 'form-control select2-card'] ) !!}
    </div>
</div>

<div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
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
</div>


