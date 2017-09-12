@extends('layouts.app')
@section('content')



     <div class="card mx-auto">
        <div class="card-header">
         Add New Handler

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
          
    
          {!! Form::model($handler = new \App\Handler, ['url' => 'handlers', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
          {!! csrf_field() !!}
          <form>
          @include('handlers.form')
          </form>
          {!! Form::close() !!}

          
        </div>
      </div>




@endsection