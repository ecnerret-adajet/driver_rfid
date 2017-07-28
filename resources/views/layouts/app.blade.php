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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet" media="screen,projection">
    </head>

    <body>
        <div id="app">

            <nav class="blue darken-2 z-depth-1">
                <div class="had-container">
                    <div class=" nav-wrapper" style="box-shadow: 0 ! important">
                      
                        <ul class="left hide-on-med-and-down">
                            <li> <a href="#!"><i class="material-icons">menu</i></a></li>
                        </ul>
                          <a href="#" class="brand-logo">Trucking Monitoring</a>
                        <ul class="right hide-on-med-and-down">
                        
                        <li><a href="badges.html"><i class="material-icons">view_module</i></a></li>
                        <li><a href="collapsible.html"><i class="material-icons">refresh</i></a></li>
                                            <!-- Dropdown Trigger -->
                            @if (Auth::guest())
                                        <li><a href="{{ route('login') }}">Login</a></li>
                                        <li><a href="{{ route('register') }}">Register</a></li>
                            @else
                                <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i></a></li>
                                <li class="dropdown">
                                    <ul id="dropdown1" class="dropdown-content">
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav> 


        @yield('content')
            


        </div>
        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
       <!-- Scripts -->
        <script src="{{ asset('js/all.js') }}"></script>
        @include('vendor.roksta.toastr')
        <script>
        $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        </script>
        @yield('script')
        


    </body>
  </html>
        
