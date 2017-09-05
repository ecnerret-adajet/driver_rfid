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

<home></home>

    <div id="wait" style="padding-top: 30px;">
        <div class="preloader-wrapper small active">
            <div class="spinner-layer spinner-green-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>


<div id="realtimeFeed"></div>


@endsection

