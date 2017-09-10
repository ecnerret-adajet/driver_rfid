@extends('layouts.app')
@section('content')

    <div class="row">
        
    <div class="col-sm-12">

     <div class="card mx-auto">
        <div class="card-header">
         Add New Driver

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
          
    
          {!! Form::model($driver = new \App\Driver, ['url' => 'drivers', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
          {!! csrf_field() !!}
          <form>
          @include('drivers.form')
          </form>
          {!! Form::close() !!}

          
        </div>
      </div>

    </div>
    </div><!-- end row -->


@endsection