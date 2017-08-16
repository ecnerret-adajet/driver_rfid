@extends('layouts.app')

@section('content')

         <div class="col s12">
            <h4 class="form-title">Print Status Page</h4>
        </div>

        @foreach($print->chunk(3) as $item)
        <div class="row">
            @foreach($item as $driver)
            <div class="col s12 m12 l4">
                <div class="card {{ $driver->notif_status == 1 ? 'red lighten-3 white-text' : '' }}">
                    <div class="card-content">
                    <span class="card-title">{{ $driver->name }}</span>
                        <p>
                            @if(count($driver->clasification) > 0)
                            Clasification: {{ $driver->clasification->name }} <br/>
                            Edited by: {{ $driver->user->name }}<br/>
                            @else
                            New Driver <br/>
                            Created by: {{ $driver->user->name }}<br/>
                            @endif
                        </p>
                    </div>
                    <div class="card-action">
                    @forelse($driver->confirms->reverse()->take(1) as $confirm)
                        @if($confirm->status ==  "Approve")
                         <form method="POST" action="{{ url('/prints',$driver->id) }}">
                         {!! csrf_field() !!}
                        <button  type="submit" class="waves-effect waves-light btn">Mark as printed</button>
                        </form>
                        @else
                        <span class="white-text center-align">
                            THIS REQUEST WAS DISAPPROVED
                        </span>
                        @endif
                    @empty
                        NOT CONFIRM IN EMAIL YET
                    @endforelse
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach

@endsection




