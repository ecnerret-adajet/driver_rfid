@extends('layouts.app')
@section('content')

    <div class="row">
        
    <div class="col-sm-12">
    
             {!! Form::model($driver = new \App\Driver, ['url' => 'drivers', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
        {!! csrf_field() !!}
        @include('drivers.form')
        {!! Form::close() !!}

    </div>
    </div><!-- end row -->


@endsection