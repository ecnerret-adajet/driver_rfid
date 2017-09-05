

<div class="col m12">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
        

                    <div class="row">
                        <div class="input-field col s12">
                           <small><lable>Pickup Number</lable></small> 
                        </div>

                        <div class="input-field col s12">
                        @if(str_contains(Request::path(), 'edit'))           
                        {!! Form::select('cardholder_list', $cardholders, $pickup->cardholder->CardholderID, ['id' => 'select2-materialize-card', 'class' => 'validate', 'placeholder' => '--- Assign a RFID ---'] ) !!}
                        @else
                        {!! Form::select('cardholder_list', $cardholders, null, ['id' => 'select2-materialize-card', 'class' => 'validate', 'placeholder' => '--- Assign a RFID ---'] ) !!}
                        @endif
                            
                            @if ($errors->has('cardholder_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('cardholder_list') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                     <div class="row">
                         <div class="input-field col s4">
                            {!! Form::text('plate_number', null,  ['class' => 'validate']) !!}
                            <label>Plate Number</label>
                            @if ($errors->has('plate_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('plate_number') }}</strong>
                                </span>
                            @endif
                        </div>
                  
                         <div class="input-field col s4">
                            {{ Form::text('driver_name', null, ['class' => 'validate']) }}
                            <label>Driver Name</label>
                            @if ($errors->has('driver_name'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('driver_name') }}</strong>
                                </span>
                            @endif
                        </div>
                  
                         <div class="input-field col s4">
                            {{ Form::text('company', null, ['class' => 'validate']) }}
                            <label>Operator</label>
                            @if ($errors->has('company'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="textarea1" name="remarks" class="materialize-textarea"></textarea>
                            <label>Remarks</label>
                            @if ($errors->has('remarks'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('remarks') }}</strong>
                                </span>
                            @endif
                        </div>
                    
                    </div>

                    <div class="row">
                        <button type="submit" class="btn waves-effect waves-light">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </div>

                </form>
            </div>
        </div>

</div>


@section('script')
    <script>
        $("#select2-materialize-card").select2({
            placeholder: "Select Pickup Number",
            allowClear: true,
        });
    </script>
@endsection