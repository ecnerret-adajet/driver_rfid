@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
            <div class="had-container">
            </div>
        </div>


        @if($driver->notif_status != 1)

        <form method="POST" action="{{ url('/confirm',$id) }}">
            {!! csrf_field() !!}
            @include('confirms.form')
        </form>

        @else

        <div class="had-container" style="padding-top: 50px;">   
            <div class="card grey lighten-4">
            <div class="card-content">
              <p style="font-size: 40px; font-weight: 300;">Ooops. We couldn't find this request :( </p>
            </div>
            <div class="card-action">
              <a href="{{url('/home')}}">back to home</a>
            </div>
            </div>
        </div>

        @endif

    </div><!-- end row -->
@endsection