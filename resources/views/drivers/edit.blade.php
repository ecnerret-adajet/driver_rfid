@extends('layouts.app')
@section('content')

     <div class="card mx-auto mb-3">
        <div class="card-header">
         Edit Driver

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
        
            @if(count($driver->image) == 0)
                <div class="mx-auto mb-3">
                    <driverupload img-url="{{ asset('/img/avatar.png') }}"></driverupload>
                </div>
            @endif

            {!! Form::model($driver, ['method' => 'PATCH','route' => ['drivers.update', $driver->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}
            <form>
            @include('drivers.form')
             </form>
            {!! Form::close() !!}
                    

     
        </div>
      </div>


@endsection