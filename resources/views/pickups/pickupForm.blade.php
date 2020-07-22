
@section('top-script')
    <style>
        .help-block {
            color: red;
            margin-left: 230px;
        }
    </style>
@endsection
   
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

<div class="form-group row {{ $errors->has('company') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
            Company
    </label>
    <div class="col-md-9">
        @if($isAllowed == "false")
            {{ Form::text('company', $userCompanyName, ['class' => 'form-control hide', 'placeholder' => 'Enter Company', 'readonly' => 'true']) }}
        @else
            {{ Form::text('company', null, ['class' => 'form-control', 'placeholder' => 'Enter Company']) }}
        @endif
    </div>
</div>
    
<div class="form-group row {{ $errors->has('do_number') ? ' has-danger' : '' }}">
    <label class="col-md-3 col-form-label">
            DO Number
    </label>
    <div class="col-md-9">
    {{ Form::text('do_number', null, ['class' => 'form-control', 'placeholder' => 'Enter DO Number']) }}
</div>
</div>

<div class="form-group row {{ $errors->has('do_number') ? ' has-danger' : '' }}">
     <label class="col-md-3 col-form-label">
        COA 
    </label>
    <div class="col-sm-9 pt-2">

    <label class="radio-inline">
      <input type="radio" name="coa" class="mr-2 p-2" value="yes">Yes
    </label>
    <label class="radio-inline">
      <input type="radio" name="coa" class="mr-2" value="no">No
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



