@extends('layouts.full')
@section('top-script')
    <script>
        setInterval(
        function(){
            $('#realtimePlant').load('http://localhost/driver_rfid/public/barrier-content');
        }, 2000);
    </script>
@endsection
@section('content')

  

            <div class="mx-auto mt-3" id="realtimePlant">
            </div>


@endsection



