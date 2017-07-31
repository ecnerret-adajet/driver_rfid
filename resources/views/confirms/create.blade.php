@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
            <div class="had-container">
            </div>
        </div>

        @if($driver->print_status == 1)

        <form method="POST" action="{{ url('/confirm',$id) }}">
            {!! csrf_field() !!}
            @include('confirms.form')
        </form>

        @else

        <div class="had-container" style="padding-top: 50px;">   
            <div class="card-panel grey lighten-4 center-align">
                <em>
                    <h3>
                         No request found, please confirm..
                    </h3>
                </em>
            </div>
        </div>

        @endif

    </div><!-- end row -->
@endsection