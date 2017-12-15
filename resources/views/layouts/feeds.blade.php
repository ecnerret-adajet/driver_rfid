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
      <style type="text/css">
        body{
            overflow-x: hidden;
        }
    </style>
    </head>

    <body>

        <div id="realtimeFeed">
            @yield('feed-section')
        </div>


    </body>
    </html>

