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

            <div class="row p-2">
                    <div class="col-sm-2">
                            @if(!empty($driver->image))
                                <img class="img-responsive rounded-circle"  style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->image->avatar)}}" align="middle">
                            @else
                                <img class="img-responsive rounded-circle"  style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->avatar)}}" align="middle">
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
                            {{$driver->truck->plate_number}}

                        <br/>
                        <br/>
                        <span class="text-muted">SUBHAULER</span><br/>
                            {{ $driver->hauler->name }}
                           

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
                    </div>            
            </div>
        </div><!-- end card-body -->
    </div> <!-- end card -->

     <div class="card mx-auto mb-3">
        <div class="card-header">
         Reprint Card
        </div>
        <div class="card-body">
              
          {!! Form::model($lost = new \App\Lost, ['url' => 'drivers/reprint/'.$driver->id, 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
          {!! csrf_field() !!}

          @include('losts.form')
          
          
          {!! Form::close() !!}     

     

        </div>
      </div> 


@endsection