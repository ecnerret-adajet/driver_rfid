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
        height: 100%
      }
    </style>

    <div id="app">

    <!-- Navigation -->
    {{--  style="background: url({{ URL::asset('img/materializebg.jpg') }}) no-repeat fixed; background-position: 20% 50%;"   --}}
    <!-- Navigation -->



    <div class="content pt-3">

      <div class="container-fluid">

  
        @yield('content')



      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->

    <footer class="sticky">
      <div class="container">
        <div class="text-center">
          <small>Copyright &copy; La Filipina Uy Gongco Group of Companies {{ Carbon\Carbon::now()->format('Y') }}</small>
        </div>
      </div>
    </footer>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Select "Logout" below if you are ready to end your current session.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

            <a class="btn btn-primary" href="javascript::void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Confirm</a>

              <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
            </form>
          
          </div>
        </div>
      </div>
    </div>


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
        <script>
          setTimeout(function() {
            location.reload();
          }, 300000); // 5 minutes page reload
        </script>

    </body>
  </html>