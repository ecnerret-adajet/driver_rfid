@extends('layouts.app')
@section('content')

        <div class="row">
            
        <div class="col-sm-12">

        <div class="card mx-auto">
            <div class="card-header">
            Edit Email

            <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                Back
            </a>
            </div>
            <div class="card-body">
            
        
               
                    {!! Form::model($setting, ['method' => 'PATCH','route' => ['settings.update', $setting->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}
                        <form>
                    @include('settings.form')
                        </form>
                    {!! Form::close() !!}

                
            </div>
        </div>

        </div>
        </div><!-- end row -->

@endsection