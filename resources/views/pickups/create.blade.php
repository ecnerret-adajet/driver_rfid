@extends('layouts.app')
@section('content')

            <div class="row">

                <div class="col s12">
                    <h4 class="form-title">New Pickup</h4>
                </div>

                {!! Form::model($pickup = new \App\Pickup, ['url' => 'pickups', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
                @include('pickups.form')
                {!! Form::close() !!}
                
            </div><!-- end row -->


@endsection