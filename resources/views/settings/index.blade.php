@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
            <div class="had-container">
            </div>
        </div>

        <settings></settings>

        {{--  <div class="had-container" style="padding-top: 50px;">
            <div class="card grey lighten-4">
                <ul id="tabs-swipe-demo" class="tabs">
                    <li class="tab col s3"><a href="#test-swipe-1">Test 1</a></li>
                    <li class="tab col s3"><a class="active" href="#test-swipe-2">Test 2</a></li>
                    <li class="tab col s3"><a href="#test-swipe-3">Test 3</a></li>
                </ul>
                <div id="test-swipe-1" class="col s12 grey lighten-4">
                Emailing <br/>
                </div>
                <div id="test-swipe-2" class="col s12 grey lighten-4"></div>
                <div id="test-swipe-3" class="col s12 grey lighten-4">Test 3</div>
            </div>
        </div>  --}}

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

