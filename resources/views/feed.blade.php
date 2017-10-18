@extends('layouts.full')
@section('top-script')
    <script>
        setInterval(
        function(){
            $('#realtimeFeed').load('{{ config('app.url') }}/driver_rfid/public/feed-content');
        }, 2000);
    </script>
@endsection
@section('content')

   <div class="card mx-auto mt-3">
        <div class="card-header">
            Truck Entries 
        </div>
        <div class="card-body">

            <div id="realtimeFeed">
            </div>

        </div><!-- end card-body -->
    </div> <!-- end card -->

@endsection



