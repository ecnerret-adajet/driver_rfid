@extends('layouts.app')
@section('content')

    <div class="row">

        <div class="col s12">
            <h4 class="form-title">Edit Truck Status</h4>
        </div>

            <div class="col s12">
                    <div class="card-panel grey lighten-4">
                        <div class="row">

                        
                            <div class="col s2">
                                <img alt="" class="circle responsive-img" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}">
                            </div>

                            <div class="col s3">

                                <div class="row">
                                    <small class="grey-text">DRIVER NAME </small><br/>
                                            {{  $driver->name }}
                                </div>

                                <div class="row">
                                    <small class="grey-text">TIME IN </small><br/>
                                    NO TRIP FOUND  
                                </div>
                            </div>

                            <div class="col s3">
                                    <div class="row">
                                    <small class="grey-text">PLATE NUMBER </small><br/>
                                            @foreach($driver->trucks as $truck)
                                                {{$truck->plate_number}}
                                            @endforeach
                                    </div>

                                    <div class="row">
                                        <small class="grey-text">TIME OUT </small><br/>
                                        NO TRIP FOUND 
                                    </div>
                            </div>

                            <div class="col s3">
                                <div class="row">
                                    <small class="grey-text">OPERATOR </small><br/>
                                            @foreach($driver->haulers as $hauler)
                                                {{$hauler->name}}
                                            @endforeach
                                    </div>


                                    <div class="row">
                                            <small class="grey-text">TIME BETWEEN </small><br/>
                                            NO TRIP FOUND
                                    </div> 
                                
                            </div>
                    

                        </div>
                    </div>
                </div>


        <form method="POST" action="{{url('/monitors/notrip/'.$monitor->id)}}">
            {!! csrf_field() !!}
            @include('monitors.form')
        </form>
    </div><!-- end row -->


@endsection