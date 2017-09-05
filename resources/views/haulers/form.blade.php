

<div class="col m12">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
        

                    <div class="row">
                        <div class="input-field col s6">
                            {{ Form::text('name', null, ['class' => 'validate']) }}
                            <label>Vendor Name</label>
                            @if ($errors->has('name'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                         <div class="input-field col s6">
                            {{ Form::text('vendor_number', null, ['class' => 'validate']) }}
                            <label>Vendor Number</label>
                            @if ($errors->has('vendor_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('vendor_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        
                         <div class="input-field col s12">
                            {{ Form::text('vendor_customer_code', null, ['class' => 'validate']) }}
                            <label>Vendor Customer Code</label>
                            @if ($errors->has('vendor_customer_code'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('vendor_customer_code') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                         <div class="input-field col s6">
                            {{ Form::text('subconvendor_name', null, ['class' => 'validate']) }}
                            <label>Subconvendor Name</label>
                            @if ($errors->has('subconvendor_name'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('subconvendor_name') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="input-field col s6">
                            {{ Form::text('subconvendor_number', null, ['class' => 'validate']) }}
                            <label>Subconvendor Number</label>
                            @if ($errors->has('subconvendor_number'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('subconvendor_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    
                    </div>

                    <div class="row">
                             <div class="input-field col s4">
                            {{ Form::text('vat_reg_tin', null, ['class' => 'validate']) }}
                            <label>Vat Registration Tin</label>
                            @if ($errors->has('vat_reg_tin'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('vat_reg_tin') }}</strong>
                                </span>
                            @endif
                        </div>

                         <div class="input-field col s4">
                            {{Form::text('address', null, ['class' => 'validate'])}}
                            <label>Address</label>
                            @if ($errors->has('address'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>

                          <div class="input-field col s4">
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
                        <div class="input-field col s12">
                        @if(str_contains(Request::path(), 'edit'))
                        {!! Form::select('server_list', $servers, count($hauler->server) == 0 ? 'null' : $hauler->server->id, ['placeholder' => 'Select Server', 'id' => 'select2-materialize-server', 'class' => 'validate'] ) !!}
                        @else
                        {!! Form::select('server_list', $servers, null, ['placeholder' => 'Select Server', 'id' => 'select2-materialize-server', 'class' => 'validate'] ) !!}
                        @endif
                            
                            @if ($errors->has('server_list'))
                                <span class="help-block red-text">
                                <strong>{{ $errors->first('server_list') }}</strong>
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
        $('select').material_select();
    </script>
@endsection