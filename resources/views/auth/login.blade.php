@extends('layouts.full')
@section('top-script')
<style>
body{
    background-color:#f5f5f5 ! important;
}
.form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="text"]
{
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.account-wall
{
    padding: 40px 0px 20px 0px;
    background-color: #ffffff;
    box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.16);
}
.login-title
{
    color: #555;
    font-size: 22px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.select-img
{
	border-radius: 50%;
    display: block;
    height: 75px;
    margin: 0 30px 10px;
    width: 75px;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.select-name
{
    display: block;
    margin: 30px 10px 10px;
}

.logo-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}

</style>
@endsection
@section('content')
<div class="container">
   <div class="card-login mx-auto">
        <div class="card-body">

                @if ($errors->has('email'))
                <div class="alert alert-danger" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                    <strong>Oh snap!</strong>  {{ $errors->first('email') }}.
                </div>
                @endif


             
            <div class="account-wall">
                <div id="my-tab-content" class="tab-content">
						<div class="tab-pane active" id="login">
               		    <img class="profile-img" src="{{ asset('img/logo.jpg') }}">


                         <form class="form-horizontal form-signin" method="POST" action="{{ route('login') }}">
                          {{ csrf_field() }}
               				<input type="email" name="email" value="{{ old('email') }}" class="form-control mt-3 mb-3 rounded-0" placeholder="Email" required autofocus>
               				
                            <input type="password" class="form-control rounded-0" name="password"  placeholder="Password" required>
               				
                               <input type="submit" class="btn btn-lg btn-default btn-block rounded-0 text-dark" value="Sign In" />
               			

               			<div id="tabs" data-tabs="tabs">
               				<p class="text-center">

                                <div class="checkbox text-center">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}><a href="#select" data-toggle="tab"> Remember Me</a>
                                    </label>
                                </div>

                            </p>

              				</div>
						</div>

                        </form>


			
					</div>
            </div>



        </div>
      </div>
    </div>




@endsection
