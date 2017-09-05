@extends('layouts.full')
@section('top-script')
    <script>
        setInterval(
        function(){
            $('#realtimeFeed').load('http://localhost/driver_rfid/public/feed-content');
        }, 2000);
    </script>
@endsection
@section('content')

    <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
        <div class="had-container">
            <div style="padding-top: 20px;">
                 <div class="spinner">
                    <div class="double-bounce1"></div>
                    <div class="double-bounce2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="had-container">

        <div class="row" style="margin-top: 20px;">
            
         


            <div id="realtimeFeed">
            </div>


        </div> <!-- end row -->


    </div>

@endsection



