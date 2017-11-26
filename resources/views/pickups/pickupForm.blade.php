
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
    {{ Form::text('company', null, ['class' => 'form-control', 'placeholder' => 'Enter Company']) }}
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
