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
            <nav class="blue darken-2 z-depth-1">
                <div class="had-container">
                    <div class=" nav-wrapper" style="box-shadow: 0 ! important">
                      
                          <a href="{{url('/home')}}" class="brand-logo">Trucking Monitoring</a>
                        <ul class="right hide-on-med-and-down">
                        
                       
                        <li>
                           Hello, {{ Auth::user()->name }}
                        </li>
                        <li>
                             <a class="button-collapse show-on-large" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="material-icons">exit_to_app</i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                 {{ csrf_field() }}
                            </form>
                        </li>
                        </ul>
                    </div>
                </div>
            </nav>
    
            @endif


        <div class="had-container">

            <div class="row" style="margin-top: 30px;">

                <div class="col s2">
        
                    <ul class="collection side-bar">
                        <li class="collection-item">
                            <a class="{{ (Request::is('/') || Request::is('home')) ? 'active' : ''}}" href="{{url('/home')}}">
                            <i class="material-icons">dashboard</i> <span class="hide-on-med-and-down"> Dashboard </span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('haulers') ||
                                          Request::is('haulers/*')
                                        ) ? 'active' : '' }}" href="{{url('/haulers')}}">
                            <i class="material-icons">cast_connected</i> <span class="hide-on-med-and-down">Haulers </span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('trucks') ||
                                          Request::is('trucks/*')  
                                      ) ? 'active' : '' }}" href="{{url('/trucks')}}">
                            <i class="material-icons">local_shipping</i> <span class="hide-on-med-and-down">Trucks</span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('drivers') ||
                                          Request::is('drivers/*')
                                        ) ? 'active' : ''}}" href="{{url('/drivers')}}">
                            <i class="material-icons">face</i> <span class="hide-on-med-and-down">Drivers</span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ Request::is('prints') ? 'active' : '' }}" href="{{url('/prints')}}">
                            <i class="material-icons">print</i> <span class="hide-on-med-and-down">Print</span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('cards') ||
                                          Request::is('bind/*')
                                        ) ? 'active' : '' }}" href="{{url('/cards')}}">
                            <i class="material-icons">credit_card</i> <span class="hide-on-med-and-down">Cards</span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('settings') ||
                                          Request::is('settings/*')
                                        ) ? 'active' : '' }}" href="{{url('/settings')}}">
                            <i class="material-icons">settings</i> <span class="hide-on-med-and-down">Settings</span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('entries') ||
                                          Request::is('generateEntries*')
                                        ) ? 'active' : '' }}" href="{{url('/entries')}}">
                            <i class="material-icons">show_chart</i> <span class="hide-on-med-and-down">Reports</span>
                            </a>
                        </li>
                        <li class="collection-item">
                            <a class="{{ (Request::is('users') ||
                                          Request::is('users/*')
                                        ) ? 'active' : '' }}" href="{{url('/users')}}">
                            <i class="material-icons">verified_user</i> <span class="hide-on-med-and-down">Users</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col s10">
                     @yield('content')
                </div>


            </div><!-- end row -->

        </div> <!-- end container -->

        </div>
       <!-- Scripts -->
        <script src="{{ asset('js/all.js') }}"></script>
        @include('vendor.roksta.toastr')
        @yield('script')


    </body>
  </html>