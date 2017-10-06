@extends('layouts.app')
@section('content')

 
     <div class="card mx-auto mb-3">
        <div class="card-header">
           Queue Remarks

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
          
    
          {!! Form::model($lineup = new \App\Lineup, ['url' => 'lineups/'.$log, 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
          {!! csrf_field() !!}
            <form>
            @include('lineups.form')
            </form>
          {!! Form::close() !!}

          
        </div>
      </div>

 


@endsection