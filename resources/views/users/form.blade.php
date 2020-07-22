


<div class="form-row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
            <label>Full Name</label>
            {{ Form::text('name', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Enter Name']) }}
            @if ($errors->has('name'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('name') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>

        <div class="col-md-6">
        <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
            <label>Email</label>
            {{ Form::text('email', null, ['type' => 'email', 'class' => 'form-control form-control-lg', 'placeholder' => 'Enter Email']) }}
            @if ($errors->has('email'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('email') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>

 <div class="form-row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('hauler_list') ? ' has-danger' : '' }}">
                    <label>Hauler</label>
                    {!! Form::select('hauler_list', $haulers, $user->hauler_id, ['placeholder' => 'Select Hauler', 'class' => 'form-control form-control-lg select2-hauler'] ) !!}
                    @if ($errors->has('hauler_list'))
                        <div class="form-control-feedback">
                        <small>
                            {{ $errors->first('hauler_list') }}
                            </small>
                        </div>
                    @endif
            </div>
        </div>
    </div>

     <div class="form-row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('company_list') ? ' has-danger' : '' }}">
                    <label>Company</label>
                    {!! Form::select('company_list', $companies, $user->company_id, ['placeholder' => 'Select Company', 'class' => 'form-control form-control-lg select2-company'] ) !!}
                    @if ($errors->has('company_list'))
                        <div class="form-control-feedback">
                        <small>
                            {{ $errors->first('company_list') }}
                            </small>
                        </div>
                    @endif
            </div>
        </div>
    </div>

     <div class="form-row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('user_location') ? ' has-danger' : '' }}">
                    <label>Location</label>
                    {!! Form::select('user_location', $userLocations, $user->user_location_id, ['placeholder' => 'Select Location', 'class' => 'form-control form-control-lg select2-location'] ) !!}
                    @if ($errors->has('user_location'))
                        <div class="form-control-feedback">
                        <small>
                            {{ $errors->first('user_location') }}
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
            {{Form::text('phone_number', null, ['class' => 'form-control form-control-lg','placeholder' => 'Phone Number', "data-inputmask" => "'mask': '+63[9999999999]'", 'data-mask'])}}
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
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
            <label>Password</label>
            <input id="password" type="password" class="form-control form-control-lg" name="password">
            @if ($errors->has('password'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('password') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>

     <div class="col-md-4">
        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <label>Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation">
            @if ($errors->has('password_confirmation'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('password_confirmation') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>

     <div class="col-md-4">
        <div class="form-group {{ $errors->has('bypass_rfid') ? ' has-danger' : '' }}">
            <label>Bypass RFID Card Assignment (For Pickup Entries)</label>



            <ul class="border-0 p-0 m-0">
            <li class="list-group-item">
                        Bypass RFID
                    <label class="switch pull-right">
                <input type="checkbox" name="bypass_rfid" value="1" class="primary" value="1" {{ old('bypass_rfid') ? 'checked="checked"' : '' }}>
                <span class="slider round"></span>
                </label>
            </li>

            </ul>

        </div>
    </div>
</div>



<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('roles_list') ? ' has-danger' : '' }}">
            <label>Role</label>
        @if(!Request::is('users/create'))
            {!! Form::select('roles_list[]',  $roles, $userRole,  ['class' => 'form-control', 'multiple']) !!}
        @else
            {!! Form::select('roles_list[]',  $roles, null,  ['class' => 'form-control', 'multiple']) !!}
        @endif
            @if ($errors->has('roles_list'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('roles_list') }}
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

    $(".select2-hauler").select2({
        placeholder: "Select Plate Number",
        allowClear: true,
    });

    $(".select2-company").select2({
        placeholder: "Select Company",
        allowClear: true,
    });

    $(".select2-location").select2({
        placeholder: "Select Company",
        allowClear: true,
    });
</script>
@endsection


