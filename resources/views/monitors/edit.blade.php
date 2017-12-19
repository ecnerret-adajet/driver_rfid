@extends('layouts.app')
@section('content')



      <div class="card mx-aut mb-3">
        <div class="card-header">
        Driver's Information

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="row">

                <div class="col-sm-2">
                    @foreach($log->drivers as $driver)
                    
                        @if(!empty($driver->image))
                            <img class="img-responsive rounded-circle"  style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->image->avatar)}}" align="middle">
                        @else
                            <img class="img-responsive rounded-circle"  style="height: 150px; width: auto;" src="{{asset('/storage/'. $driver->avatar)}}" align="middle">
                        @endif
                        
                    @endforeach                
                </div>

                <div class="col-sm-4">
                    <span class="text-muted">DRIVER NAME:</span><br/>
                    @foreach($log->drivers as $driver)
                        {{  $driver->name }}
                    <br/> 
                    <br/>
                    <span class="text-muted">PLATE NUMBER:</span><br/>
                        @foreach($driver->trucks as $truck)
                            {{$truck->plate_number}}
                    <br/>
                    <br/>
                    <span class="text-muted">HAULER:</span><br/>
                     @if($truck->vendor_number == null)
                        @foreach($truck->haulers as $hauler)
                                {{ $hauler->name }}
                        @endforeach
                    @else
                        {{ $truck->vendor_number }}
                    @endif
                      @endforeach
                    @endforeach

                </div>

                <div class="col-sm-4">

                    <span class="text-muted">TIME IN:</span><br/>
                     <?php $final_in = ''; ?>
                    @forelse($all_in->where('CardholderID', '==', $log->CardholderID)->take(1) as $in)
                        {{ $final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}}
                    @empty
                        <?php $final_in = Carbon\Carbon::now() ?>
                        NO IN   
                    @endforelse  
                    <br/>
                    <br/>
                    <span class="text-muted">TIME OUT:</span><br/>
                     <?php $final_out = ''; ?>
                    @forelse($all_out->where('CardholderID', '==', $log->CardholderID)->take(1) as $out)
                        {{ $final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}}
                        <br/>
                    @empty
                        NO OUT
                    @endforelse
                    <br/>
                    <br/>
                    <span class="text-muted">DURATION TIME:</span>   
                    @forelse($all_out->where('CardholderID', '==', $log->CardholderID)->take(1) as $out )
                    @forelse($all_in->where('CardholderID', '==', $log->CardholderID)->take(1) as $in )
                        {{  $in->LocalTime->diffInHours($out->LocalTime)}} Hour(s)
                    @empty
                        NO PAIRED TIME IN
                    @endforelse
                    @empty
                        NO PAIRED TIME OUT
                    @endforelse 
                
                </div>
                <div class="col-sm-2">
                    <span class="text-muted">STATUS</span><br/>
                     @foreach($log->drivers as $driver)
                        @if($driver->availability == 1)
                            <span class="badge badge-primary">
                                ACTIVE
                            </span>
                        @else
                            <span class="badge badge-warning">
                                INACTIVE
                            </span>
                        @endif
                    @endforeach
                </div>  

            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->


    
    <div class="card mx-auto mb-3">
        <div class="card-header">
        Create Truck Status

        </div>
        <div class="card-body">
            
        {!! Form::model($monitor, ['method' => 'PATCH','route' => ['monitors.update', $monitor->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}
            <form>
            @include('monitors.form')
            </form>
        {!! Form::close() !!}


        </div><!-- end card-body -->
    </div> <!-- end card -->






@endsection