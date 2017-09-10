@extends('layouts.app')
@section('content')

     <div class="row">
        
    <div class="col-sm-12">

     <div class="card mx-auto">
        <div class="card-header">
         Edit Driver

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
          <form>

            {!! Form::model($driver, ['method' => 'PATCH','route' => ['drivers.update', $driver->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}

            @include('drivers.form')
            
            {!! Form::close() !!}
                    

      </form>
        </div>
      </div>

    </div>
    </div><!-- end row -->

@endsection