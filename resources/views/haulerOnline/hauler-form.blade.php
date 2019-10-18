


<div class="form-row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
            <label>Full Name</label>
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name']) }}
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
            {{ Form::text('email', null, ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter Email']) }}
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
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
            <label>Password</label>
            <input id="password" type="password" class="form-control" name="password">
            @if ($errors->has('password'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('password') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>

     <div class="col-md-6">
        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <label>Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            @if ($errors->has('password_confirmation'))
                    <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('password_confirmation') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>




<button type="submit"  class="btn btn-primary btn-block">Submit</button>

@section('script')
<!-- <script>
    $("[data-mask]").inputmask();
</script> -->
@endsection


