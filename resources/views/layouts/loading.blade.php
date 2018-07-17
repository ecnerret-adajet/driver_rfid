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

    @yield('top-script')

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/lfuggoc.ico') }}">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet" media="screen,projection">
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    </head>

    <body id="page-top">

    <style>
      html, body {
        background: #fff;
        height: 100%
      }
    </style>

    <div id="app">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top mb-2" id="mainNav">
      <a class="navbar-brand" href="{{url('/home')}}">Truck Monitoring</a>
    </nav>

    <div class="content" style="margin-top: 90px;">
      <div class="container-fluid">

        @yield('content')

      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content-wrapper -->

    
      </div><!-- end app -->
    
       <!-- Scripts -->
        <script src="{{ asset('js/all.js') }}"></script>
        {{--  <script src="{{ asset('js/jquery.cropit.js') }}"></script>  --}}
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>        
        <script src="{{ asset('js/sb-admin.js') }}"></script>
        @yield('script')
        @include('flashy::message')
        <script>
          $(".navbar-sidenav").addClass("thin");
            // If user has Javascript disabled, the thick scrollbar is shown
            $(".navbar-sidenav").mouseover(function(){
              $(this).removeClass("thin");
            });
            $(".navbar-sidenav").mouseout(function(){
              $(this).addClass("thin");
            });
            $(".navbar-sidenav").scroll(function () {
              $("body").addClass("thin");
            });
        </script>
    </body>
  </html>