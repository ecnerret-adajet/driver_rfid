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

    <div id="wait" class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;">
        <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
            <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
        </svg>	
    </div>
       
<div id="realtimeFeed"></div>


@endsection

