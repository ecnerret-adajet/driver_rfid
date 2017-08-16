@extends('layouts.app')
@section('content')

            <div class="row">
              


                    {!! Form::model($setting, ['method' => 'PATCH','route' => ['settings.update', $setting->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                    @include('settings.form')
                    
                    {!! Form::close() !!}
                            
                   




                </div><!-- end row -->


@endsection