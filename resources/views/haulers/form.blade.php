

<div class="col m12">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
        

                    <div class="row">
                        <div class="input-field col s12">
                            {{ Form::text('name', null, ['class' => 'validate']) }}
                            <label>Hauler Number</label>
                            @if ($errors->has('name'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                          
                        <div class="input-field col s12">
                            {{Form::text('address', null, ['class' => 'validate'])}}
                            <label>Address</label>
                            @if ($errors->has('address'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">

                         <div class="input-field col s12">
                            {{ Form::text('contact_number', null, ['class' => 'validate']) }}
                            <label>Contact Number</label>
                            @if ($errors->has('contact_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('contact_number') }}</strong>
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


