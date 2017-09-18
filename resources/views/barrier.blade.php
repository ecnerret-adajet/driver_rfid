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

   <div class="card mx-auto mt-3">
        <div class="card-header">
            Plant Entries 
        </div>
        <div class="card-body">

            <div id="realtimePlant">
            </div>

        </div><!-- end card-body -->
    </div> <!-- end card -->

@endsection



