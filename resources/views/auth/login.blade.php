@extends('layouts.full')

@section('content')

<div class="container">
<div class="card-login mx-auto mt-5">
<img class="img-responsive center ml-4" src="{{ asset('img/logo.png') }}" style="height: 130px; width: auto;">
</div>
   <div class="card card-login mx-auto mt-2">
        
        <div class="card-header">
          Login
        </div>
        <div class="card-body">
          <form class="form-horizontal" method="POST" action="{{ route('login') }}">
           {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
              <label>Email address</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" require placeholder="Enter Email" class="form-control">
                 @if ($errors->has('email'))
                    <div class="form-control-feedback">
                            <small>
                            {{ $errors->first('email') }}
                            </small>
                        </div>
                @endif
            </div>
            <div class="form-group">
              <label>Password</label>
               <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                @if ($errors->has('password'))
                    <div class="form-control-feedback">
                            <small>
                            {{ $errors->first('password') }}
                            </small>
                        </div>
                @endif
            </div>
          
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </form>
        </div>
      </div>
    </div>




@endsection
