@extends('layouts.app')

@section('content')

    <div class="row light-blue darken-1 white-text">
        <div class="had-container">
            <div class="col s12">
                <h5>To Print RFID</h5>
            </div>
        </div>
    </div>

    <div class="had-container">
        @foreach($print->chunk(4) as $item)
        <div class="row">
            @foreach($item as $driver)
            <div class="col s3">
                <div class="card {{ $driver->notif_status == 1 ? 'blue-grey darken-1 white-text' : '' }}">
                    <div class="card-content">
                    <span class="card-title">{{ $driver->name }}</span>
                    <p>
                        Clasification: {{ $driver->clasification->name }} <br/>
                        Edited by: {{ $driver->user->name }}<br/>
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
    </div>

@endsection




