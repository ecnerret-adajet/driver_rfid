@extends('layouts.app')

@section('content')

    <div class="row">

        <settings></settings>

    </div><!-- end row -->





@endsection
@section('script')
  <script>
      $(document).ready(function(){
        $('ul.tabs').tabs({
            swipeable: 'true' 
        });
    });
  </script>
@endsection

