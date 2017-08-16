@extends('layouts.app')
@section('content')

            <div class="row">
                   
                    <div class="col s12">
                    <h4 class="form-title">Edit Truck</h4>
                     </div>


                    {!! Form::model($truck, ['method' => 'PATCH','route' => ['trucks.update', $truck->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}


                    @include('trucks.form')

                            
                    {!! Form::close() !!}
                            
                   




                </div><!-- end row -->


@endsection