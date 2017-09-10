@extends('layouts.app')
@section('top-script')
    <script>
   
        setInterval(function(){
        $('#realtimeFeed').load('http://localhost/driver_rfid/public/home-content');
        $('#wait').hide();     
        }, 2000);
     
     
            
    </script>
@endsection
@section('content')

  <!-- Breadcrumbs -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">My Dashboard</li>
    </ol>


    <home></home>

       
    <div id="wait" style="padding-top: 30px;">
        <div class="loader center">Loading...</div>
    </div>


<div id="realtimeFeed"></div>


@endsection

