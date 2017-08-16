@extends('layouts.app')
@section('content')

    <div class="row">
        
        <div class="col s12">
            <h4 class="form-title">Create Driver</h4>
        </div>

        {!! Form::model($driver = new \App\Driver, ['url' => 'drivers', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
        {!! csrf_field() !!}
        @include('drivers.form')
        {!! Form::close() !!}
    </div><!-- end row -->


@endsection