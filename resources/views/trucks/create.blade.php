@extends('layouts.app')
@section('content')

            <div class="row">

                <div class="col-sm-12">

                {!! Form::model($truck = new \App\Truck, ['url' => 'trucks', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
                @include('trucks.form')
                {!! Form::close() !!}


                </div>

                
            </div><!-- end row -->


@endsection