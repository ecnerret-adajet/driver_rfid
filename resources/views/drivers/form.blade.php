      

                @if(!Request::is('drivers/create'))
                    <div class="form-group {{ $errors->has('clasification_list') ? ' has-danger' : '' }}">
                        <div class="form-row">
                            <div class="col-md-12">
                            <label>Clasification</label>
                            @if(count($driver->clasification) == 0)
                            {{ Form::select('clasification_list', $clasifications, null, ['class' => 'form-control', 'placeholder' => 'Select Clasification']) }}
                            @else
                            {{ Form::select('clasification_list', $clasifications, $driver->clasification->id, ['class' => 'form-control', 'placeholder' => 'Select Clasification']) }}
                            @endif
                            @if ($errors->has('clasification_list'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('clasification_list') }}
                                    </small>
                                </div>
                            @endif
                            </div> 
                        </div>
                    </div>
                @endif
         
                <div class="form-row">
                     <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputFile">Upload Photo</label>
                            <input type="file" name="avatar" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                            <small id="fileHelp" class="form-text text-muted"></small>
                        </div>
                    </div>
                </div>

                 <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('card_list') ? ' has-danger' : '' }}">
                                <label for="selectCard">RFID Card</label>
                                @if(str_contains(Request::path(), 'edit'))
                                {!! Form::select('card_list', $cards, count($driver->card) == 0 ? 'null' : $driver->card->CardID, ['placeholder' => 'Select Deploy RFID', 'id' => 'selectCard select2-materialize-card', 'class' => 'form-control'] ) !!}
                                @else
                                {!! Form::select('card_list', $cards, null, ['placeholder' => 'Select Deploy RFID', 'id' => 'selectCard select2-materialize-card', 'class' => 'form-control'] ) !!}
                                @endif
                                @if ($errors->has('card_list'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('card_list') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('truck_list') ? ' has-danger' : '' }}">
                                <label for="selectCard">Plate Number</label>
                                {!! Form::select('truck_list', $trucks, null, ['placeholder' => 'Select Plate Number', 'id' => 'selectCard select2-materialize-truck', 'class' => 'form-control'] ) !!}
                                @if ($errors->has('truck_list'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('truck_list') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
                        <label for="driverName">Full Name</label>
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'driverName', 'placeholder' => 'Enter First Name']) }}
                        @if ($errors->has('name'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('name') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>

              </div>

            <div class="form-row">
               
                    <div class="col-md-6">
                     <div class="form-group {{ $errors->has('driver_license') ? ' has-danger' : '' }}">
                    <label for="driverLicense">License Number</label>
                    {{Form::text('driver_license', null, ['class' => 'form-control', 'placeholder' => 'Enter License Number', "data-inputmask" => "'mask': 'a99-99-999999'", 'data-mask'])}}
                    @if ($errors->has('driver_license'))
                            <div class="form-control-feedback">
                                <small>
                                {{ $errors->first('driver_license') }}
                                </small>
                            </div>
                        @endif
                    </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('nbi_number') ? ' has-danger' : '' }}">
                        <label for="nbiNumber">NBI Number</label>
                        {{ Form::text('nbi_number', null, ['class' => 'form-control', 'placeholder' => 'Enter NBI Number', "data-inputmask" => "'mask': '99999999'", 'data-mask']) }}
                        @if ($errors->has('nbi_number'))
                            <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('nbi_number') }}
                                    </small>
                                </div>
                        @endif
                        </div>
                    </div>

                </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('phone_number') ? ' has-danger' : '' }}">
                    <label>Phone Number</label>
                    {{Form::text('phone_number', null, ['class' => 'form-control','placeholder' => 'Phone Number', "data-inputmask" => "'mask': '[9999999999]'", 'data-mask'])}}
                       @if ($errors->has('phone_number'))
                            <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('phone_number') }}
                                    </small>
                                </div>
                        @endif
                    </div>
                </div>
            </div>
            
             <button type="submit"  class="btn btn-primary btn-block">Submit</button>
    

 


@section('script')
    <script>

        $("[data-mask]").inputmask();
    
        $("#select2-materialize-truck").select2({
            placeholder: "Select Plate Number",
            allowClear: true,
        });

        $("#select2-materialize-hauler").select2({
            placeholder: "Select Operator",
            allowClear: true,
        });

        $("#select2-materialize-rfid").select2({
            placeholder: "Select RFID",
            allowClear: true,
        });

        $("#select2-materialize-card").select2({
            placeholder: "Select Card",
            allowClear: true,
        });

    </script>
@endsection