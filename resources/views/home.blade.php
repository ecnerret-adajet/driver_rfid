@extends('layouts.app')

@section('content')

<div class="had-container">

  <div class="row">      
    <div class="col s4">
      <div class="card light-blue">
        <div class="card-content">
          <div id="test1" style="display: none;" class="white-text">
              <span class="title">
                TOTAL HAULERS
              </span>
              <h1>
                30
              </h1>
          </div>
          <div id="test2" class="white-text" style="display: none;">
              <span class="title">
                TOTAL TRUCKS
              </span>
              <h1>
                30
              </h1>
          </div>

          <div id="test3" style="" class="white-text active">
              <span class="title">
                TOTAL DRIVERS
              </span>
              <h1>
             30
              </h1>
          </div>
        </div>
        <div class="card-tabs white">
          <ul class="tabs tabs-fixed-width tabs-transparent">
          <li class="tab">
            <a href="#test1" class="blue-text">
              Total Hauler
            </a>
          </li>
          <li class="tab">
            <a class="light-blue-text" href="#test2">
            Total Trucks
            </a>
          </li>
          <li class="tab">
            <a href="#test3" class="blue-text active">
                 Total Drivers
            </a>
          </li>
          <li class="indicator" style="right: 0px; left: 238px; background-color: #03a9f4;"></li></ul>
          </div>

      </div>
    </div>


    <div class="col s4">
   <div class="card teal darken-2">
            <div class="card-content white-text">
              <span >PENDING TO PRINT</span>
              <h1>
                20
              </h1>
            </div>
            <div class="card-action">
              <a href="{{ url('/prints') }}" class="waves-effect waves-light">View details</a>
            </div>
          </div>
  </div>


</div> <!-- end row -->

<!-- temp
<div class="row">
    <div class="col s12">
      <ul class="collection with-header">
      <li class="collection-item avatar">
      <i class="material-icons circle red darken-2">access_time</i>
      <span class="title">User Logs</span>
      <p>User activity within the portal</p>
      <a href="#!" class="secondary-content waves-effect waves-light btn light-blue z-depth-0">See More</a></a>
    </li>
        <li class="collection-item">
          <div class="row collections-row">
            <div class="col s5">
            <p class="collections-title">
              Administrator
            </p>
            <p class="collections-content">
              Some Logs
            </p>
            </div>
            <div class="col s7 right-align">
                 <div class="chip blue lighten-1 white-text">
                 Created
                </div>
            </div>
          </div>
        </li>
      </ul>
    </div>

</div>
-->

@endsection
@section('script')
  <script>
      $(document).ready(function(){
        $('ul.tabs').tabs();
    });
  </script>
@endsection

