@extends('layouts.app')
@section('content')

            <div class="row">

                <div class="col s12">
                    <h4 class="form-title">Bind RFID Number</h4>
                </div>


                

                <form method="POST" action="{{url('/bind/'.$card->CardID)}}">
                {!! csrf_field() !!}

                    @include('binders.form')
                
                </form> 

                                           
                   
            </div><!-- end row -->


@endsection