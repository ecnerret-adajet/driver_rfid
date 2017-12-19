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
                          @foreach($driver->trucks as $truck)
                            {{$truck->plate_number}}
                          @endforeach

                        <br/>
                        <br/>
                        <span class="text-muted">HAULER</span><br/>
                        @foreach($driver->trucks as $truck)
                             @foreach($truck->haulers as $hauler)
                                {{ $hauler->name }}
                        @endforeach
                        @endforeach


                        

                    </div>
                    <div class="col-sm-3">
                        <span class="text-muted">SUBVENDOR</span><br/>
                        

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
         Transfer Driver to New Hauler

        </div>
        <div class="card-body">

            {!! Form::model($driver, ['method' => 'PATCH','route' => ['transfer-hauler.update', $driver->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}

            <form>

            <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('hauler_list') ? ' has-danger' : '' }}">
                                <label>Hauler Name</label>
                                {!! Form::select('hauler_list', $haulers, null, ['placeholder' => 'Select Hauler', 'class' => 'form-control select2-hauler'] ) !!}
                                @if ($errors->has('hauler_list'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('hauler_list') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>


               <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('truck_list') ? ' has-danger' : '' }}">
                                <label>Plate Number</label>
                                {!! Form::select('truck_list', $trucks, null, ['placeholder' => 'Select Plate Number', 'class' => 'form-control select2-truck'] ) !!}
                                @if ($errors->has('truck_list'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('truck_list') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>



             <button type="submit"  class="btn btn-primary btn-block">Submit</button>
            </form>
            {!! Form::close() !!}
                    

            
        </div>
      </div>



@endsection
@section('script')
<script>
      $(".select2-hauler").select2({
            placeholder: "Select Hauler",
            allowClear: true,
        });

        $(".select2-truck").select2({
            placeholder: "Select Plate Number",
            allowClear: true,
        });
</script>
@endsection