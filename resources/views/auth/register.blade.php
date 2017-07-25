@extends('layouts.app')

@section('content')
<div class="container">


    <div class="row">  
        <div class="col m12">
            <h3>Register</h3>
        </div>
        <div class="col m12">
            <div class="card-panel grey lighten-4 hoverable">
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}


                    <div class="row form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <div class="input-field col s12">
                            <input id="name" type="text" class="validate" name="name" value="{{ old('name') }}" required>
                            <label for="name">Name</label>
                            @if ($errors->has('name'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="input-field col s12">
                            <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" required>
                            <label for="email">Email</label>

                            @if ($errors->has('email'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="input-field col s12">
                            <input id="password" type="password" class="validate" name="password" required>
                            <label for="password">Password</label>

                            @if ($errors->has('password'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="input-field col s12">
                            <input id="password-confirm" type="password" class="validate" name="password_confirmation" required>
                            <label for="password-confirm">Confirm Password</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col m6">
                            <button type="submit" class="btn btn-primary">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
