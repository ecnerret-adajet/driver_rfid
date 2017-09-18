@extends('layouts.app')
@section('content')

          <div class="row">

                <div class="col-sm-12">

                 <div class="card mx-auto">
                    <div class="card-header">
                        Edit Truck

                        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                        Back
                        </a>
                    </div>
                    <div class="card-body" id="form-truck">
                      


                    {!! Form::model($truck, ['method' => 'PATCH','route' => ['trucks.update', $truck->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                      <form>
                       @include('trucks.form')
                    </form>
                            
                    {!! Form::close() !!}
                            
                   

                 
                </div>
                </div>



                </div>

                
            </div><!-- end row -->


@endsection