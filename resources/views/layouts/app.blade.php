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

    <title>Truck Monitoring</title>

    <!-- Styles -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/lfuggoc.ico') }}">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet" media="screen,projection">
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">

    <div id="app">

    <!-- Navigation -->
    {{--  style="background: url({{ URL::asset('img/materializebg.jpg') }}) no-repeat fixed; background-position: 20% 50%;"   --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top pb-1" id="mainNav">
      <a class="navbar-brand" href="{{url('/')}}">
        <img class="rounded-circle" src="{{ asset('img/logo.jpg') }}" style=" width: 30px; height: auto;">
        Truck Monitoring
      </a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

          <li class="nav-item {{ Request::is('home') ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Dashboard">
            <a class="nav-link font-weight-bold" href="{{url('/home')}}">
               <i class="fa fa-fw fa-dashboard"></i>
              <span class="nav-link-text">
                Dashboard</span>
            </a>
          </li>

          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Master Data">
            <a class="nav-link nav-link-collapse font-weight-bold {{ (Request::is('trucks') ||
                                                     Request::is('trucks/*') ||
                                                     Request::is('pickups-entries') ||
                                                     Request::is('pickups/*') ||
                                                     Request::is('generatePickups') ||
                                                     Request::is('generateLineups') ||
                                                     Request::is('lineups') ||
                                                     Request::is('lineups/*') ||
                                                     Request::is('drivers') ||
                                                     Request::is('replacements') ||
                                                     Request::is('drivers/*') ||
                                                     Request::is('cards') ||
                                                     Request::is('bind/*')) ? '' : 'collapsed' }}" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
               <i class="fa fa-fw fa-database"></i>
              <span class="nav-link-text">
                Master Data</span>
            </a>
            <ul class="sidenav-second-level {{ (Request::is('trucks') ||
                                                     Request::is('trucks/*') ||
                                                     Request::is('drivers') ||
                                                     Request::is('replacements') ||
                                                     Request::is('lineups') ||
                                                     Request::is('generatePickups') ||
                                                     Request::is('generateLineups') ||
                                                     Request::is('lineups/*') ||
                                                     Request::is('pickups-entries') ||
                                                     Request::is('pickups/*') ||
                                                     Request::is('drivers/*') ||
                                                     Request::is('cards') ||
                                                     Request::is('bind/*')) ? '' : 'collapse' }}" id="collapseComponents">

              @role((['Administrator','Monitoring','Approver','spc-monitoring']))

              <li class="font-weight-bold {{ (Request::is('trucks') ||
                            Request::is('trucks/*')
                        ) ? 'active' : '' }}">
                <a href="{{url('/trucks')}}">Trucks</a>
              </li>

               @endrole

              @role((['Administrator','Monitoring','Approver']))

              <li class="font-weight-bold {{ (Request::is('drivers') ||
                            Request::is('drivers/*')
                          ) ? 'active' : ''}}">
                <a href="{{url('/drivers')}}">Drivers</a>

              </li>

              <li class="font-weight-bold {{ (Request::is('replacements') ||
                            Request::is('replacements/*')
                          ) ? 'active' : ''}}">
                <a href="{{url('/replacements')}}">Card Replacements</a>

              </li>

               @endrole

               @role((['Administrator','Personnel']))

               <li class="font-weight-bold {{ (Request::is('pickups-entries') ||
                            Request::is('generatePickups') ||
                            Request::is('pickups/*')
                          ) ? 'active' : ''}}">
                <a href="{{url('/pickups-entries')}}">Pickups</a>
              </li>

              @endrole

              @role((['Administrator','Queue-monitoring']))

              <li class="font-weight-bold {{ (Request::is('lineups') ||
                            Request::is('generateLineups') ||
                            Request::is('lineups/*')
                          ) ? 'active' : ''}}">
                <a href="{{url('/queueEntryFeed')}}">Queue</a>
              </li>

              @endrole

              @role((['Administrator','Queue-monitoring','monitoring']))

              <li class="font-weight-bold {{ Request::is('expected-arrivals*') ? 'active' : ''}}">
                <a href="{{url('/expected-arrivals')}}">ETA</a>
              </li>

              @endrole

              @role((['Administrator','Queue-monitoring','monitoring']))

              <li class="font-weight-bold {{ Request::is('booking-requests*') ? 'active' : ''}}">
                <a href="{{url('/booking-requests')}}">Booking Entries</a>
              </li>

              @endrole


            </ul>
          </li>

          @role(('Administrator'))



          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Admin">
            <a class="nav-link nav-link-collapse font-weight-bold {{ (Request::is('settings') ||
                                                     Request::is('users') ||
                                                     Request::is('users/*') ||
                                                     Request::is('generateActivities') ||
                                                     Request::is('settings/*') ||
                                                     Request::is('handlers') ||
                                                     Request::is('handlers/*') ||
                                                     Request::is('activities')) ? '' : 'collapsed' }}" data-toggle="collapse" href="#collapseMulti" data-parent="#exampleAccordion">
               <i class="fa fa-fw fa-user"></i>
              <span class="nav-link-text">
                Administrator</span>
            </a>
            <ul class="sidenav-second-level {{ (Request::is('settings') ||
                                                     Request::is('generateActivities') ||
                                                     Request::is('users') ||
                                                     Request::is('gates/create') ||
                                                     Request::is('queues/create') ||
                                                     Request::is('users/*') ||
                                                     Request::is('settings/*') ||
                                                     Request::is('handlers') ||
                                                     Request::is('handlers/*') ||
                                                     Request::is('activities')) ? '' : 'collapse' }}" id="collapseMulti">
              <li class="font-weight-bold {{ (Request::is('gates/create') ||
                              Request::is('queues/create')
                            ) ? 'active' : '' }}">
                <a href="{{url('/gates/create')}}">Entries Monitoring</a>
              </li>
              <li class="font-weight-bold {{ (Request::is('users') ||
                              Request::is('users/*')
                            ) ? 'active' : '' }}">
                <a href="{{url('/users')}}">Users</a>
              </li>
              <li class="font-weight-bold {{ (Request::is('settings') ||
                              Request::is('settings/*')
                            ) ? 'active' : '' }}">
                <a href="{{url('/settings')}}">Settings</a>
              </li>
               <li class="font-weight-bold {{ (Request::is('activities') || Request::is('generateActivities')) ? 'active' : '' }}">
                <a href="{{url('/activities')}}">System Logs</a>
              </li>
              {{--  <li class="{{ (Request::is('handlers') || Request::is('handlers/*')) ? 'active' : '' }}">
                <a href="{{url('/handlers')}}">Hauler Mapping</a>
              </li>  --}}
            </ul>
          </li>

          @endrole


          @role((['Administrator','Monitoring','Approver']))

          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Reports">
            <a class="nav-link nav-link-collapse font-weight-bold {{ (Request::is('prints') ||
                                                     Request::is('analytics') ||
                                                     Request::is('entries') ||
                                                     Request::is('generateEntries*') ||
                                                     Request::is('monitors/*'))  ? '' : 'collapsed' }}" data-toggle="collapse" href="#collapseReports" data-parent="#exampleAccordion">
               <i class="fa fa-fw fa-bar-chart"></i>
              <span class="nav-link-text">
                Reports</span>
            </a>
            <ul class="sidenav-second-level {{ (Request::is('prints') ||
                                                     Request::is('analytics') ||
                                                     Request::is('entries') ||
                                                     Request::is('generateEntries*') ||
                                                     Request::is('monitors/*')) ? '' : 'collapse' }}" id="collapseReports">

              <li class="font-weight-bold {{ (Request::is('analytics')) ? 'active' : '' }}">
                <a href="{{url('/analytics')}}">Status Report</a>
              </li>

              <li class="font-weight-bold {{ (Request::is('prints')) ? 'active' : '' }}">
                <a href="{{url('/prints')}}">Drivers Approval</a>
              </li>

               <!-- <li class="font-weight-bold {{ (Request::is('entries') ||
                                  Request::is('generateEntries*') ||
                                  Request::is('monitors/*')
                                ) ? 'active' : '' }}">
                <a href="{{url('/entries')}}">Vehicle Entries Report</a>
              </li> -->

            </ul>
          </li>

          @endrole

          @role(('Administrator'))

           {{--  <li class="nav-item {{ Request::is('feed') ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Ship Entries">
            <a class="nav-link" href="{{url('/feed')}}">
               <i class="fa fa-fw fa-file"></i>
              <span class="nav-link-text">
                Truckscale Entries</span>
            </a>
          </li>

           <li class="nav-item {{ Request::is('barrier') ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Plant Entries">
            <a class="nav-link" href="{{url('/barrier')}}">
               <i class="fa fa-fw fa-file"></i>
              <span class="nav-link-text">
                Plant Entries</span>
            </a>
          </li>  --}}

        @endrole

        </ul>


        <ul class="navbar-nav sidenav-toggler">
           <li class="nav-item">
            <a class="nav-link text-center" id="sidenavToggler">
              <i class="fa fa-fw fa-angle-left"></i>
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">


          <li class="nav-item">
            <a class="nav-link mr-lg-2" href="#">
             Hello, {{ Auth::user()->name }}
            </a>
          </li>

         <li class="dropdown">
            <a class="nav-link  mr-lg-2" href="#" id="messagesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-fw fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="messagesDropdown">
              <h6 class="dropdown-header">Options:</h6>
              <div class="dropdown-divider"></div>

              @if(!Entrust::hasRole('Administrator'))
                <a class="dropdown-item" href="{{ url('online/users/'.Auth::user()->id.'/edit') }}">
                  <strong>Profile</strong>
                </a>
              @endif

              <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal">
                <strong>Logout</strong>
              </a>
            </div>
          </li>



        </ul>
      </div>
    </nav>

    <div class="content-wrapper">

      <div class="container-fluid pt-2">


        @yield('content')



      </div>
      <!-- /.container-fluid -->

    </div>
    <!-- /.content-wrapper -->

    <footer class="sticky-footer">
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

    </body>
  </html>
