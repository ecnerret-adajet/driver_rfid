

<div class="had-container" style="padding-top: 50px;">
    <div class="col m8">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
                    <div class="row">
                       <div class="input-field col s12">
                       @if(count($driver->clasification) == 0)
                        {{ Form::select('clasification_list', $clasifications, null, ['class' => 'validate', 'placeholder' => 'Select Clasification']) }}
                       @else
                        {{ Form::select('clasification_list', $clasifications, $driver->clasification->id, ['class' => 'validate', 'placeholder' => 'Select Clasification']) }}
                       @endif
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
    </div> <!-- end col m8 -->
    <div class="col m4">
        <div class="card-panel grey lighten-5">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" disabled class="validate" value="100">
                    <label>Cost:</label>
                </div>
            </div>
             <div class="row">
                <div class="input-field col s12">
                    <input type="text" disabled class="validate" value="{{$driver->update_count == null ? 0 : $driver->update_count}}">
                    <label>Print Count:</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" disabled class="validate" value="{{$driver->user->name}}">
                    <label>Last Edited by:</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" disabled class="validate" value="{{ date('F d,Y', strtotime($driver->updated_at)) == 'January 01,1970' ? '-- -- --' :  date('F d,Y', strtotime($driver->updated_at)) }}">
                    <label>Update Date:</label>
                </div>
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