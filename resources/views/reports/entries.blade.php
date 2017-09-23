<?php
    session_start();
    @$sel_hauler = $_GET["hauler_list"];
    @$sel_start = $_GET["start_date"];
    @$sel_end = $_GET["end_date"];
    $_SESSION["redirect_lnk"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    if(!empty( $_GET["start_date"]) || !empty( $_GET["end_date"]) || !empty( $_GET["hauler_list"])) {
        $_SESSION["start_date"] = $_GET["start_date"];
        $_SESSION["end_date"] = $_GET["end_date"];
        $_SESSION["hauler_list"] = $_GET["hauler_list"];
    }
?>
@extends('layouts.app')
@section('content')


    <div class="card mx-auto mb-3">
        <div class="card-header">
            Vehicle Trip Report
        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

             @if (session('status')) 
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger" role="alert">
                        <strong>Oh snap!</strong> Change a few things up and try submitting again.
                    </div>
                </div>
            </div>
             @endif

             <div class="row">
                <div class="col-sm-12">
                     {{ Form::open(array('url' => '/generateEntries', 'method' => 'get')) }}
                        <form>


                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('hauler_list') ? ' has-danger' : '' }}">
                                        <label>Operator</label>
                                         {!! Form::select('hauler_list[]', $haulers, $sel_hauler, ['class' => 'form-control select2-hauler', 'multiple'=>'multiple'] ) !!}
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
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('start_date') ? ' has-danger' : '' }}">
                                        <label>Start Date</label>
                                        {!! Form::input('date','start_date', Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'max' => ''.date('Y-m-d', strtotime(Carbon\Carbon::now())).'' ]) !!}
                                        @if ($errors->has('start_date'))
                                            <div class="form-control-feedback">
                                            <small>
                                                {{ $errors->first('start_date') }}
                                                </small>
                                            </div>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('end_date') ? ' has-danger' : '' }}">
                                        <label>End Date</label>
                                        {!! Form::input('date', 'end_date',  Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'max' => ''.date('Y-m-d', strtotime(Carbon\Carbon::now())).'' ]) !!} 
                                        @if ($errors->has('end_date'))
                                            <div class="form-control-feedback">
                                            <small>
                                                {{ $errors->first('end_date') }}
                                                </small>
                                            </div>
                                        @endif
                                </div>
                            </div>
                        </div>

                        <button type="submit"  class="btn btn-primary btn-block">Generate</button>
                        
                        </form>
                    {!! Form::close() !!} 
                </div>             
             </div>

            
        </div><!-- end card-body -->
    </div> <!-- end card -->


    <div class="card mx-auto mb-3">
        <div class="card-header">
        Entries Result

         @if(Request::is('generateEntries*'))
            <a href="{{ url('/generateEntriesExport') }}"  class="btn btn-primary btn-sm pull-right mr-2">Export to xls</a>
        @endif
        </div>
        <div class="card-body">



           <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Hauler</th>
                                        <th>Driver</th>
                                        <th>Plate Number</th>
                                        @if(!empty($start_date) && !empty($end_date))
                                            @for ($x = $start_date; $x <= $end_date; $x=date('Y-m-d', strtotime($x. ' + 1 days')))
                                                <th class="center-align">
                                                    {{ date('F d', strtotime($x)) }}
                                                </th>
                                            @endfor
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($today_result as $today)

                                            @foreach($today->drivers as $driver)
                                                @foreach($driver->haulers as $hauler)

                                            <tr>
                                                <td>{{$hauler->name}}</td>
                                                <td>{{$driver->name}}</td>
                                                <td>
                                                    @foreach($driver->trucks as $truck)
                                                         @if($truck->plate_number == null)
                                                            {{  $truck->reg_number }}
                                                        @else
                                                            {{  $truck->plate_number }}
                                                        @endif  
                                                    @endforeach
                                                </td>

                                                @if(!empty($start_date) && !empty($end_date))
                                                @for ($x = $start_date; $x <= $end_date; $x=date('Y-m-d', strtotime($x. ' + 1 days')))
                                                
                                                <td class="center">

                                                    @forelse(App\Log::where('CardholderID',$today->CardholderID)
                                                        ->whereDate('LocalTime' ,Carbon\Carbon::parse($x))
                                                        ->orderBy('LocalTime','ASC')
                                                        ->get() as $value => $trip)

                                                                @if($value == 0)
                                                                    @if(empty($trip->monitors()->count()))
                                                                        <a href="{{url('/monitors/create/'.$trip->LogID)}}">
                                                                                <i class="material-icons green-text">check_circle</i>
                                                                        </a>
                                                                    @else
                                                                        @foreach($trip->monitors->reverse()->take(1) as $monitor)
                                                                        <a href="{{url('/monitors/'.$monitor->id.'/edit/'.$trip->LogID) }}">
                                                                            <i class="material-icons blue-text">book</i>
                                                                        </a>
                                                                        @endforeach
                                                                    @endif
                                                                @endif            

                                                    @empty

                                                        @forelse($monitors->where('driver_id',$driver->id)->where('ship_date',Carbon\Carbon::parse($x))->take(1)  as $monitor)
                                                            <a href="{{url('/monitors/notrip/'.$monitor->id.'/'.$driver->id.'/edit/')}}">
                                                                <i class="material-icons red-text">book</i>
                                                            </a>
                                                        @empty
                                                            <a href="{{url('/monitors/notrip/'.Carbon\Carbon::parse($x).'/'.$driver->id)}}">
                                                                <i class="material-icons red-text">cancel</i>
                                                            </a>
                                                        @endforelse
                                                                                                            
                                                    @endforelse

                                                </td>

                                                @endfor
                                                @endif
                                            </tr>

                                                @endforeach
                                            @endforeach
                                        @endforeach
                                </tbody>
                                </table>


                    </div>
                </div>
           </div>



        </div><!-- end card-body -->
    </div> <!-- end card -->

@endsection
@section('script')
<script>
     $(".select2-hauler").select2();
</script>
@endsection

