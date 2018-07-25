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

        <!--Import Google Icon Font-->

        <!-- Styles -->
        <link rel="shortcut icon" type="image/png" href="{{ asset('img/lfuggoc.ico') }}">
        <link href="{{ asset('css/all.css') }}" rel="stylesheet" media="screen,projection">

        <style>
            html, body {
                background-color: #f5f5f5 ! important;
            }
        </style>

    </head>

    <body class="fixed-nav sticky-footer" id="page-top">
        <div id="app">


            @if(Request::is('manila') || Request::is('lapaz') || Request::is('bataan') || Request::is('users/hauler/online'))
            <!-- Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="mainNav">
                <a class="navbar-brand" href="{{url('/home')}}">Truck Monitoring</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                </div>
            </nav>
            @endif



            <div class="content">

                <div class="container-fluid">


                    @yield('content')



                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- /.content-wrapper -->

        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/all.js') }}"></script>
        @yield('script')


    </body>
    </html>

