<!DOCTYPE html>
  <html lang="{{ app()->getLocale() }}">
  <html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
    </script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet" media="screen,projection">
    </head>

    <body>
        <div id="app">

             @if (!Auth::guest())

            <ul id="slide-out" class="side-nav">
                <li>
                    <div class="user-view">
                        <div class="background">
                            <img src=" {{ asset('img/bg1.png') }}">
                        </div>
                        <a href="#!user"><img class="circle" src=" {{ asset('img/avatar.png') }}"></a>
                        <a href="#!name"><span class="white-text name">{{ Auth::user()->name }}</span></a>
                        <a href="#!email"><span class="white-text email">{{ Auth::user()->email }} </span></a>
                    </div>
                </li>
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/haulers') }}">Haulers</a></li>
                <li><a href="{{ url('/trucks') }}">Trucks</a></li>
                <li><a href="{{ url('/drivers') }}">Drivers</a></li>
                <li><a href="{{ url('/cards') }}">Cards</a></li>
                <li><a href="{{ url('/settings') }}">Settings</a></li>
                <li><a href="#!">Users</a></li>
                <li><div class="divider"></div></li>
                <li><a class="waves-effect" 
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                </li>
            </ul>

            <nav class="blue darken-2 z-depth-1">
                <div class="had-container">
                    <div class=" nav-wrapper" style="box-shadow: 0 ! important">
                      
                          <a href="{{url('/home')}}" class="brand-logo">Trucking Monitoring</a>
                        <ul class="right hide-on-med-and-down">
                        
                       
                        <li><a href="#" data-activates="slide-out" class="button-collapse show-on-large"><i class="material-icons">menu</i></a></li>
                        </ul>
                    </div>
                </div>
            </nav>
    
            @endif


                     @yield('content')
            


            </div><!-- end row -->

        </div> <!-- end container -->

       
            


        </div>
       <!-- Scripts -->
        <script src="{{ asset('js/all.js') }}"></script>
        <script>
        $(document).ready(function(){
            $('.button-collapse').sideNav({
                edge: 'right'
            });
        });
        </script>
        @include('vendor.roksta.toastr')
        @yield('script')


    </body>
  </html>
        
