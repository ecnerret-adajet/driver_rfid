

<div class="had-container" style="padding-top: 50px;">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
        

                    <div class="row">
                        <div class="input-field col s12">
                            {{ Form::text('plate_number', null, ['class' => 'validate']) }}
                            <label>Plate Number</label>
                            @if ($errors->has('plate_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('plate_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                       <div class="input-field col s12">
                             {{Form::select('hauler_list', $haulers, null, ['placeholder' => 'Select Operator', 'class' => 'validate'])}}
                            {{--  <label>Operator</label>  --}}
                            @if ($errors->has('hauler_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('hauler_list') }}</strong>
                                </span>
                            @endif
                        </div> 
                    </div>

                    <div class="row">
                          
                        <div class="input-field col s6">
                            {{Form::text('vehicle_type', null, ['class' => 'validate'])}}
                            <label>Vehicle Type</label>
                            @if ($errors->has('vehicle_type'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('vehicle_type') }}</strong>
                                </span>
                            @endif
                        </div>


                         <div class="input-field col s6">
                            {{ Form::text('capacity', null, ['class' => 'validate']) }}
                            <label>Capacity</label>
                            @if ($errors->has('capacity'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('capacity') }}</strong>
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
        $("#select2-materialize-hauler").select2({
            placeholder: "Select Operator",
            allowClear: true,
        });
    </script>
@endsection