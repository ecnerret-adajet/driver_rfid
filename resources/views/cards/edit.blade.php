@extends('layouts.app')
@section('content')

            <div class="row">
                    <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
                        <div class="had-container">
                        </div>
                    </div>


                    <form method="POST" action="{{url('/cards/'.$card->CardID)}}">

.                    {!! csrf_field() !!}

                    @include('cards.form')
                    
                    </form>                            
                   




                </div><!-- end row -->


@endsection