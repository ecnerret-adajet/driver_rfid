@extends('layouts.app')
@section('content')

            <div class="row">
                    <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
                        <div class="had-container">
                            <a href="{{url('/home')}}" class="waves-effect waves-light btn blue">
                            <i class="material-icons">arrow_back</i>  
                            </a>                               


                            <a class="waves-effect waves-light btn blue right" href="{{url('/trucks/create')}}">
                            Add Truck
                            </a>
                        </div>
                    </div>

                    {!! Form::model($driver = new \App\Driver, ['url' => 'drivers', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                    {!! csrf_field() !!}
                    @include('drivers.form')
                    {!! Form::close() !!}
                   




                </div><!-- end row -->


@endsection