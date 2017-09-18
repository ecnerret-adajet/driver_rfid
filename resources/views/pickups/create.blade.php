@extends('layouts.app')
@section('content')


 
     <div class="card mx-auto mb-3">
        <div class="card-header">
         Add New Pickup

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
          
    
          {!! Form::model($pickup = new \App\Pickup, ['url' => 'pickups', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
          <form>
          @include('pickups.form')
          </form>
          {!! Form::close() !!}

          
        </div>
      </div>

@endsection