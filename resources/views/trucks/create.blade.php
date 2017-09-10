@extends('layouts.app')
@section('content')

            <div class="row">

                <div class="col-sm-12">

                 <div class="card mx-auto">
                    <div class="card-header">
                        Add New Truck

                        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                        Back
                        </a>
                    </div>
                    <div class="card-body">
                      

                {!! Form::model($truck = new \App\Truck, ['url' => 'trucks', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
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