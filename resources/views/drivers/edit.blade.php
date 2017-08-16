@extends('layouts.app')
@section('content')

            <div class="row">

                <div class="col s12">
                    <h4 class="form-title">Edit Driver</h4>
                </div>

                    {!! Form::model($driver, ['method' => 'PATCH','route' => ['drivers.update', $driver->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                    @include('drivers.form')
                    
                    {!! Form::close() !!}
                            
                   




                </div><!-- end row -->


@endsection