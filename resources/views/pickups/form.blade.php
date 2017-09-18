
<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('cardholder_list') ? ' has-danger' : '' }}">
                <label>RFID Card</label>
                @if(str_contains(Request::path(), 'edit'))
                {!! Form::select('cardholder_list', $cardholders, $pickup->cardholder->CardholderID, ['placeholder' => 'Select Pickup Number',  'class' => 'form-control select2-pickup'] ) !!}
                @else
                {!! Form::select('cardholder_list', $cardholders, null, ['placeholder' => 'Select Pickup Number', 'class' => 'form-control select2-pickup'] ) !!}
                @endif
                @if ($errors->has('cardholder_list'))
                    <div class="form-control-feedback">
                    <small>
                        {{ $errors->first('cardholder_list') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>


<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('plate_number') ? ' has-danger' : '' }}">
            <label>Plate Number</label>
            {{ Form::text('plate_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Plate Number']) }}
            @if ($errors->has('plate_number'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('plate_number') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>


<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
            <label>Driver Name</label>
            {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Driver Name']) }}
            @if ($errors->has('driver_name'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('driver_name') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>


<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('company') ? ' has-danger' : '' }}">
            <label>Company</label>
            {{ Form::text('company', null, ['class' => 'form-control', 'placeholder' => 'Enter Company']) }}
            @if ($errors->has('company'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('company') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
            <label>Remarks</label>
            {{ Form::textarea('remarks', null, ['class' => 'form-control', 'placeholder' => 'Enter Remarks','rows' => '3']) }}
            @if ($errors->has('remarks'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('remarks') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>


<button type="submit"  class="btn btn-primary btn-block">Submit</button>




@section('script')
    <script>
        $(".select2-pickup").select2({
            placeholder: "Select Pickup Number",
            allowClear: true,
        });
    </script>
@endsection