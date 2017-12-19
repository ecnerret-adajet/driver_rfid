@extends('layouts.app')

@section('content')
@inject('search', 'App\Http\Controllers\DriversController')

   <div class="card mx-auto mb-3">
        <div class="card-header">
        Driver's Information
        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
            @role(('Administrator'))
                <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/drivers/'.$driver->id.'/edit') }}">
                    Edit Driver
                </a>
            @endrole
        </div> 
        <div class="card-body">

            <div class="row p-2">
                    <div class="col-sm-2">

                        @if(!empty($driver->image))
                            <img class="img-responsive rounded-circle" style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->image->avatar)}}">
                        @else
                            <img class="img-responsive rounded-circle" style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->avatar)}}">
                        @endif

                    </div>
                    <div class="col-sm-4">
                        <span class="text-muted">DRIVER NAME</span><br/>
                         {{ $driver->name }}
                        <br/>
                        <br/>
                        <span class="text-muted">PHONE NUMBER</span><br/>
                        {{ $driver->phone_number }}
                    </div>
                    <div class="col-sm-3">
                        <span class="text-muted">PLATE NUMBER</span><br/>
                          @foreach($driver->trucks as $truck)
                            {{$truck->plate_number}}

                        <br/>
                        <br/>
                        <span class="text-muted">SUBHAULER</span><br/>
                                @foreach($driver->haulers as $hauler)
                                        {{ $hauler->name }}
                                @endforeach
                           
                          @endforeach

                    </div>
                    <div class="col-sm-3">
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
                        <br/>
                        <br/>
                       
                        <span class="text-muted">ASSIGNED CARD</span><br/>
                        {{ $driver->card->full_deploy }}

                        <br/>
                        <br/>
                        <span class="text-muted">REGISTERED BY</span><br/>
                        {{ $driver->user->name }}

                    </div>            
            </div>
        </div><!-- end card-body -->
    </div> <!-- end card -->


    <div class="card mx-auto mb-3">
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
                                @foreach($log->drivers->take(1)->reverse() as $driver)
                                    @foreach($driver->trucks as $truck)
                                        {{$truck->plate_number}}
                                    @endforeach
                                @endforeach  
                            </td>
                            <td>
                                @foreach($log->drivers->take(1) as $driver)
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


        <div class="card mx-auto mb-3">
        <div class="card-header">
            Driver's History

        </div>
        <div class="card-body">

        

        <table class="table">
        <thead>
            <tr>
                <th>CardholderID</th>
                <th>CardID</th>
                <th>Name</th>
                <th>Plate #</th>
                <th>Vendor</th>
                <th>Update Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($versions as $version)
            <tr>
            <td>
                 {{$version->cardholder_id}}
            </td>
            <td>
                 {{$version->card_no}}
            </td>
             <td>
                @if(!empty($version->cardholder_id))
                    {{ $search->getCardholderName($version->cardholder_id) }}                    
                @endif
            </td>
            <td>
                {{$version->plate_number}}
            </td>
            <td>
                {{$version->vendor}}
            </td>
            <td>
                {{ date('m/d/Y h:i:s A', strtotime($version->created_at))}}
            </td>
            </tr>
        @endforeach
        </tbody>
        </table>
            
        

        
    </div>
    </div>


@endsection


