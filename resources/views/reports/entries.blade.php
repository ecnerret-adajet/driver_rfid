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
    <div class="row">

        <div class="col s12">
            <h4 class="form-title">Report Entries</h4>
        </div>

         @if (session('status')) 
        <div class="col s12">
            <div class="card red white-text">
                <div class="card-content gray-text">
                    <strong>Oh snap!</strong>   {{ session('status') }}
                    </div>
            </div>
        </div>
         @endif

        <div class="col s12">
        <ul class="collapsible popout" data-collapsible="expandable">
            <li>
                <div class="collapsible-header active">Generate Entries</div>
                <div class="collapsible-body grey lighten-5" style="padding: 0;">

                    <div class="row">
                        <div class="col s12">
                            

                                {{ Form::open(array('url' => '/generateEntries', 'method' => 'get')) }}


                                    <div class="row">
                                        <div class="input-field col s12">
                                        {{--  <label>Operator</label>  --}}
                                        {!! Form::select('hauler_list[]', $haulers, $sel_hauler, ['id' => 'select2-materialize-hauler', 'class' => 'validate','multiple'] ) !!}

                                
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s4">
                                            {!! Form::input('date','start_date', $sel_start, ['class' => 'form-control']) !!}
                                            {{--  <label>Start Date</label>  --}}
                                            @if ($errors->has('start_date'))
                                                <span class="help-block red-text">
                                                <strong>{{ $errors->first('start_date') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="input-field col s4">
                                            {!! Form::input('date', 'end_date', $sel_end, ['class' => 'form-control', 'max' => ''.date('Y-m-d', strtotime(Carbon\Carbon::now())).'' ]) !!} 
                                            {{--  <label>Start Date</label>  --}}
                                            @if ($errors->has('end_date'))
                                                <span class="help-block red-text">
                                                <strong>{{ $errors->first('end_date') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="input-field col s4">
                                            <button style="width: 100%" type="submit" class="z-depth-0 blue btn waves-effect waves-light">Generate
                                                <i class="material-icons right">send</i>
                                            </button>
                                        </div>
                                    </div>
                                {!! Form::close() !!} 



                            
                        </div><!-- end col s12 -->
                    </div><!-- end row -->
                        
                </div><!-- end first section -->
            </li>
            <li>
                <div class="collapsible-header active">
                        <div class="col s6">Today's Entries</div>
                        <div class="col s6 right-align">
                            @if(Request::is('generateEntries*'))
                                <a href="{{ url('/generateEntriesExport') }}" class="waves-effect blue waves-light btn btn-small z-depth-0 ">Export to xls</a>
                            @endif
                        </div>
                </div>
                <div class="collapsible-body grey lighten-5" style="padding: 0;">
                
                    <div class="row">
                        <div class="col s12">
                                    <table class="reports-table highlight">
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
                                                        {{$truck->plate_number}}
                                                    @endforeach
                                                </td>

                                                @if(!empty($start_date) && !empty($end_date))
                                                @for ($x = $start_date; $x <= $end_date; $x=date('Y-m-d', strtotime($x. ' + 1 days')))
                                                
                                                <td class="center-align">

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
                                                                <i class="material-icons red-text">cancel</i>
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
                        </div><!-- end col s12 -->
                    </div><!-- end row -->
                
                </div><!-- end second section -->
            </li>
        </ul>
        </div>
  


        

    </div><!-- end row -->
@endsection

@section('script')
    <script>
        $("#select2-materialize-hauler").select2({
            placeholder: "Select Operator",
            allowClear: true,
        });   

        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15 // Creates a dropdown of 15 years to control year
        });     
    </script>
@endsection

