<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        table thead tr th {
            background-color: #f1c40f;
        }
        tr > td, tr > th {
            border: 1px solid #000000;
        }

        .has-trip {
           background-color: #2ecc71;
        }

        .no-trip {
            background-color: #e74c3c;
        }

    </style>
    </head>

        <table class="table table-hover">
                <thead>
                <tr>
                <th>HAULER</th>
                <th>DRIVER</th>
                <th>PLATE NUMBER</th>

                @foreach($top_header as $header)                                
                <th>
                    {{  strtoupper($header) }}
                </th>
                @endforeach

                </tr>
                </thead>

                <tbody>
                    @foreach($today_result as $today)
                    @foreach($today->drivers as $driver)
                    @foreach($driver->haulers as $hauler)

                    <tr>
                            <td>
                                {{$hauler->name}}
                            </td>

                            <td>
                                {{$driver->name}}
                            </td>

                            <td>
                            @foreach($driver->trucks as $truck)
                                {{ $truck->plate_number }}
                            @endforeach
                            </td>

                            @foreach($result_array as $result) 
                                @forelse(App\Log::where('CardholderID',$today->CardholderID)
                                ->whereDate('LocalTime' ,Carbon\Carbon::parse($result))->get()
                                 as $value => $trip)
                                    @if($value == 0)
                                            @if(empty($trip->monitors()->count()))
                                            <td class="no-trip">
                                            NO REPORT
                                            </td>
                                            @else

                                            @foreach($trip->monitors as $monitor)
                                            <td class="has-trip">
                                                {{ $monitor->location->code }}{{ $monitor->status->code }}{{ $monitor->duration->days }}{{ $monitor->detail->code }}
                                            </td>

                                            @endforeach
                                            @endif
                                    @endif
                                @empty
                                            <td>
                                            XXXXXXXX
                                            </td>
                                @endforelse
                            @endforeach

                        

                    </tr>
                </tbody>

                    @endforeach
                    @endforeach
                    @endforeach
            </table>

</html>