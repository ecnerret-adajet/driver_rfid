@extends('layouts.app')
@section('content')


<div class="card mx-auto mb-3">
        <div class="card-header">
        Driver's Information

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="row">

                <div class="col-sm-2">
                
                        @if(!empty($driver->image))
                            <img class="img-responsive rounded-circle"  style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->image->avatar)}}" align="middle">
                        @else
                            <img class="img-responsive rounded-circle"  style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->avatar)}}" align="middle">
                        @endif
                
                </div>

                <div class="col-sm-4">
                    <span class="text-muted">DRIVER NAME:</span><br/>
                        {{  $driver->name }}
                    <br/> 
                    <br/>
                    <span class="text-muted">PLATE NUMBER:</span><br/>
                        @foreach($driver->trucks as $truck)
                            {{$truck->plate_number}}
                    <br/>
                    <br/>
                    <span class="text-muted">HAULER:</span><br/>
                     @if($truck->vendor_description == null)
                        @foreach($truck->haulers as $hauler)
                                {{ $hauler->name }}
                        @endforeach
                    @else
                        @foreach($truck->haulers as $hauler)
                            {{ $truck->vendor_description }}
                        @endforeach
                    @endif
                        @endforeach
                    

                </div>

                <div class="col-sm-4">

                 
                
                </div>

                <div class="col-sm-2">
                    <span class="text-muted">STATUS</span><br/>
                        @if($driver->availability == 1)
                            <span class="badge badge-primary">
                                ACTIVE
                            </span>
                        @else
                            <span class="badge badge-warning">
                                INACTIVE
                            </span>
                        @endif
                </div>  

            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->

    <div class="card mx-auto mb-3">
        <div class="card-header">
        Create Truck Status

        </div>
        <div class="card-body">


            {!! Form::model($monitor = new \App\Monitor, ['url' => '/monitors/'.$id, 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
            {!! csrf_field() !!}
            <form>
            @include('monitors.form')
            </form>
            {!! Form::close() !!}


        </div><!-- end card-body -->
    </div> <!-- end card -->




@endsection