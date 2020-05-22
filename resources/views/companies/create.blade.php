@extends('layouts.app')
@section('content')


     <div class="card mx-auto mb-3">
        <div class="card-header">
         Add Company

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">

          {!! Form::model($company = new \App\Company, ['url' => 'companies', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
          {!! csrf_field() !!}
          <form>
          @include('companies.form')
          </form>
          {!! Form::close() !!}



        </div>
      </div>


@endsection
