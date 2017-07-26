

<div class="row" style="padding-top: 50px;">
    <div class="col m8 offset-m2">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">

                    <div class="row">
                       <div class="input-field col s12">
                       
                        {{ Form::select('clasification_list', $clasifications, null, ['class' => 'validate', 'placeholder' => 'Select Clasification']) }}
                        <label>Clasification</label>
                        @if ($errors->has('clasification_list'))
                            <span class="help-block red-text">
                                <strong>{{ $errors->first('clasification_list') }}</strong>
                            </span>
                        @endif
                        </div> 
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                        @if(str_contains(Request::path(), 'edit'))
                        {!! Form::select('cardholder_list', $cardholders, $driver->cardholder->CardholderID, ['class' => 'validate', 'placeholder' => 'Select Deploy RFID'] ) !!}
                        @else
                        {!! Form::select('cardholder_list', $cardholders, null, ['class' => 'validate', 'placeholder' => 'Select Deploy RFID'] ) !!}
                        @endif
                            <label>Deploy RFID</label>
                            @if ($errors->has('cardholder_list'))
                                <span class="help-block red-text">
                                <strong>{{ $errors->first('cardholder_list') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            {{ Form::text('name', null, ['class' => 'validate']) }}
                            <label>Driver Name</label>
                            @if ($errors->has('name'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="input-field col s6">
                            {{Form::number('driver_number', null, ['class' => 'validate'])}}
                            <label>Deploy Number</label>
                            @if ($errors->has('driver_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('driver_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                       <div class="input-field col s6">
                        {{ Form::select('truck_list', $trucks, null, ['class' => 'validate', 'placeholder' => 'Select Plate Number']) }}
                        <label>Plate Number</label>
                            @if ($errors->has('truck_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('truck_list') }}</strong>
                                </span>
                            @endif
                        </div> 

                        <div class="input-field col s6">
                            {{Form::select('hauler_list', $haulers, null, ['class' => 'validate', 'placeholder' => 'Select Operator'])}}
                            <label>Operator</label>
                            @if ($errors->has('hauler_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('hauler_list') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s6">
                            {{Form::text('phone_number', null, ['class' => 'validate'])}}
                            <label>Phone Number</label>
                             @if ($errors->has('phone_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="input-field col s6">
                            {{Form::text('substitute', null, ['class' => 'validate'])}}
                            <label>Substitute</label>
                              @if ($errors->has('substitute'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('substitute') }}</strong>
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
</div>


@section('script')
    <script>
        $('.select2-materialize').select2();
        $('select').material_select();
    </script>
@endsection