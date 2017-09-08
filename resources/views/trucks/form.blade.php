

    <div class="col s12">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12" id="trucks">
        

                    <div class="row">
                        <div class="input-field col s6">
                            {{ Form::text('plate_number', null, ['class' => 'validate']) }}
                            <label>Plate Number</label>
                            @if ($errors->has('plate_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('plate_number') }}</strong>
                                </span>
                            @endif
                        </div>

                          <div class="input-field col s6">
                            {{ Form::text('reg_number', null, ['class' => 'validate']) }}
                            <label>Registration Number</label>
                            @if ($errors->has('reg_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('reg_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                        @if(str_contains(Request::path(), 'edit'))
                        {!! Form::select('card_list', $cards, count($truck->card) == 0 ? 'null' : $truck->card->CardID, ['placeholder' => 'Select Deploy RFID', 'id' => 'select2-materialize-card', 'class' => 'validate'] ) !!}
                        @else
                        {!! Form::select('card_list', $cards, null, ['placeholder' => 'Select Deploy RFID', 'id' => 'select2-materialize-card', 'class' => 'validate'] ) !!}
                        @endif
                            
                            @if ($errors->has('card_list'))
                                <span class="help-block red-text">
                                <strong>{{ $errors->first('card_list') }}</strong>
                                </span>
                            @endif
                        </div>

                       
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                           
                            {!! Form::select('capacities_list', $capacities, null, ['placeholder' => 'Select Deploy RFID', 'id' => 'select2-materialize-capacity', 'class' => 'validate'] ) !!}
                            
                            @if ($errors->has('card_list'))
                                <span class="help-block red-text">
                                <strong>{{ $errors->first('card_list') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                  
                           
                        <vendor></vendor>
                     

                    <div class="row">
                          <div class="input-field col s12">
                            {!! Form::select('contract_list', $contracts, null, ['placeholder' => 'Select Contract Code', 'id' => 'select2-materialize-contract', 'class' => 'validate'] ) !!}
                            @if ($errors->has('contract_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('contract_list') }}</strong>
                                </span>
                            @endif
                        </div>

                    
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <input name="validity_start_date" type="text" class="datepicker">
                            <label>Validity Start Date</label>
                            @if ($errors->has('validity_start_date'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('validity_start_date') }}</strong>
                                </span>
                            @endif
                        </div>


                         <div class="input-field col s6">
                              <input name="validity_end_date" type="text" class="datepicker">
                            <label>Validity End Date</label>
                            @if ($errors->has('validity_end_date'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('validity_end_date') }}</strong>
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

        
        $("#select2-materialize-card").select2({
            placeholder: "Select Card",
            allowClear: true,
        });

        $("#select2-materialize-contract").select2({
            placeholder: "Select Contract Code",
            allowClear: true,
        });

        $("#select2-materialize-capacity").select2({
            placeholder: "Select Capacity",
            allowClear: true,
        });

        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year,
            today: 'Today',
            clear: 'Clear',
            close: 'Ok',
            closeOnSelect: false // Close upon selecting a date,
        });
        
    </script>
@endsection