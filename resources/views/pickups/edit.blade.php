@extends('layouts.app')
@section('content')
            <div class="row">   
                    <div class="col s12">
                    <h4 class="form-title">Edit Pickup</h4>
                     </div>

                    {!! Form::model($pickup, ['method' => 'PATCH','route' => ['pickups.update', $pickup->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                    @include('pickups.form')
                            
                    {!! Form::close() !!}
            
                </div><!-- end row -->

@endsection