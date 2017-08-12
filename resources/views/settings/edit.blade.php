@extends('layouts.app')
@section('content')

            <div class="row">
                    <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
                        <div class="had-container">
                        </div>
                    </div>


                    {!! Form::model($setting, ['method' => 'PATCH','route' => ['settings.update', $setting->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                    @include('settings.form')
                    
                    {!! Form::close() !!}
                            
                   




                </div><!-- end row -->


@endsection