@extends('layouts.app')

@section('content')

<div class="container">

  <div class="row">
      <div class="col m8 offset-m2 ">
        <div class="card-panel grey lighten-4 hoverable">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                           <div class="input-field col s12">
                                <input id="email" type="email" name="email" value="{{ old('email') }}" require class="validate">
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




                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
        </div>
      </div>
    </div>


</div>
@endsection
