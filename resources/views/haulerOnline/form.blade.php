
                 <div class="form-row">
                     <div class="col-md-12">
                        <div class="form-group {{ $errors->has('avatar') ? ' has-danger' : '' }}">
                            <label for="exampleInputFile">Upload Photo</label>
                            <input type="file" name="avatar" class="form-control-file filestyle"  data-size="sm" data-btnClass="btn-primary" data-buttonBefore="true" data-size="lg" id="exampleInputFile">
                            @if ($errors->has('avatar'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('avatar') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
         
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('truck_list') ? ' has-danger' : '' }}">
                                <label>Plate Number</label>
                                {!! Form::select('truck_list', $trucks, null, ['placeholder' => 'Select Plate Number', 'class' => 'form-control select2-truck'] ) !!}
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
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'driverName', 'placeholder' => 'Enter Full Name']) }}
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
                    {{Form::text('driver_license', null, ['class' => 'form-control', 'placeholder' => 'Enter License Number', "data-inputmask" => "'mask': 'A99-99-999999'", 'data-mask'])}}
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
                    {{Form::text('phone_number', null, ['class' => 'form-control','placeholder' => 'Phone Number', "data-inputmask" => "'mask': '+63[9999999999]'", 'data-mask'])}}
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


              <div class="form-row">


                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('address') ? ' has-danger' : '' }}">
                        <label for="driverName">Address</label>
                        {{ Form::text('address', null, ['class' => 'form-control', 'id' => 'driverName', 'placeholder' => 'Enter Address']) }}
                        @if ($errors->has('address'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('address') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>


              </div>


            <div class="form-row">
               
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('contact_person') ? ' has-danger' : '' }}">
                        <label for="driverName">Contact Person</label>
                        {{ Form::text('contact_person', null, ['class' => 'form-control', 'id' => 'driverName', 'placeholder' => 'Enter Contact Person']) }}
                        @if ($errors->has('contact_person'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('contact_person') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>


                    <div class="col-md-6">
                    <div class="form-group {{ $errors->has('contact_phone') ? ' has-danger' : '' }}">
                    <label>Phone Number</label>
                    {{Form::text('contact_phone', null, ['class' => 'form-control','placeholder' => 'Contact Phone Number', "data-inputmask" => "'mask': '+63[9999999999]'", 'data-mask'])}}
                       @if ($errors->has('contact_phone'))
                            <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('contact_phone') }}
                                    </small>
                                </div>
                        @endif
                    </div>
                </div>

                </div>

            
             <button type="submit"  class="btn btn-primary btn-block">Publish</button>
    

 


@section('script')
    <script>
        $("[data-mask]").inputmask();
     
        $(".select2-truck").select2({
            placeholder: "Select Plate Number",
            allowClear: true,
        });

        $(":file").filestyle({btnClass: "btn-primary", buttonBefore: true, size: "sm"});

    </script>
@endsection