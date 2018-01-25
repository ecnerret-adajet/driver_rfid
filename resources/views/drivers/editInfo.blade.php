@extends('layouts.app')
@section('content')

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

                        @if(!count($driver->trucks) == 0)
                            {{ $driver->trucks->first()->plate_number }}
                        @else
                            NO TRUCK ASSIGNED
                        @endif
                         
                        <br/>
                        <br/>
                        <span class="text-muted">SUBHAULER</span><br/>

                        @if(!count($driver->haulers) == 0)
                            {{ $driver->haulers->first()->name }}
                        @else
                            NO HAULER
                        @endif


                        <br/>
                        <br/>
                        <span class="text-muted">CREATED DATE</span><br/>
                         {{  date('F d, Y h:i:s A', strtotime($driver->created_at))}}

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
                        
                        @if($driver->card->CardholderID == $driver->cardholder->CardholderID)
                            {{ $driver->card->full_deploy }} <br/>
                        @else
                            <span class="text-danger">No Card Assign</span>
                        @endif
                     
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
         Edit Driver

      
        </div>
        <div class="card-body">
        

            {!! Form::model($driver, ['method' => 'PATCH','route' => ['drivers.updateInfo', $driver->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}

                <form>
                    @include('drivers.formInfo')
                </form>

            {!! Form::close() !!}
                    

     
        </div>
      </div>


@endsection