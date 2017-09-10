@extends('layouts.app')

@section('content')

   <div class="card mx-auto">
        <div class="card-header">
        Driver's Information
        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/drivers/'.$driver->id.'/edit') }}">
            Edit Driver
        </a>
        </div> 
        <div class="card-body">

            <div class="row p-2">
                    <div class="col-sm-2">
                        <img class="img-responsive img-rounded" style="height: 150px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}">
                    </div>
                    <div class="col-sm-4">
                        <span class="text-muted">DRIVER NAME</span>
                         {{ $driver->name }}
                        <br/>
                        <br/>
                        <span>PHONE NUMBER</span>
                        {{ $driver->phone_number }}
                    </div>
                    <div class="col-sm-4">
                        <span class="text-muted">PLATE NUMBER</span>
                          @foreach($driver->trucks as $truck)
                            {{$truck->plate_number}}

                        <span class="text-muted">HAULER</span>
                            @if($truck->vendor_description == null)
                                @foreach($truck->haulers as $hauler)
                                        {{ $hauler->name }}
                                @endforeach
                            @else
                                {{ $truck->vendor_description }}
                            @endif
                          @endforeach

                    </div>
                    <div class="col-sm-2">
                        <span class="text-muted">STATUS</span>
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


    <div class="card mx-auto">
        <div class="card-header">
        Driver's Log
        </div> 
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" id="dataTable" cellspacing="0">
                    <thead>
                        <tr>
                        <th>LogID</th>
                        <th>Plate Number</th>
                        <th>Cardholder #</th>
                        <th>Direction</th>
                        <th>Time</th>
                        <th>Snapshot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs  as $log)
                        <tr>
                            <td>
                                {{$log->LogID}}
                            </td>
                            <td>
                                @foreach($log->drivers as $driver)
                                    @foreach($driver->trucks as $truck)
                                        {{$truck->plate_number}}
                                    @endforeach
                                @endforeach  
                            </td>
                            <td>
                                @foreach($log->drivers as $driver)
                                    {{$driver->cardholder_id}}
                                @endforeach
                            </td>
                            <td>
                                {{ $log->Direction == 1 ? 'IN' : 'OUT' }}
                            </td>
                            <td>
                                 {{  date('F d, Y h:i:s A', strtotime($log->LocalTime))}}
                            </td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($log->LocalTime)).'/AC.'.date('Ymd',strtotime($log->LocalTime)).'.0000'.$log->LogID.'-1.jpg')}}" data-lightbox="{{$log->LogID}}" data-title="TIME IN - {{  date('Y-m-d h:i:s A', strtotime($log->LocalTime))}}">                      
                                    Camera
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection


