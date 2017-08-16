@extends('layouts.app')
@section('content')

            <div class="row">

                 <div class="col s12">
                    <h4 class="form-title">Add New Truck</h4>
                </div>


                {!! Form::model($truck = new \App\Truck, ['url' => 'trucks', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
                @include('trucks.form')
                {!! Form::close() !!}
                
            </div><!-- end row -->


@endsection