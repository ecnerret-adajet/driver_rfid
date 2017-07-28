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
                <div class="card">
                    <div class="card-content">
                    <span class="card-title">{{ $driver->name }}</span>
                    <p>
                        Clasification: {{ $driver->clasification->name }} <br/>
                        Edited by: {{ $driver->user->name }}<br/>
                    </p>
                    </div>
                    <div class="card-action">
                    <form method="POST" action="{{ url('/prints',$driver->id) }}">
                         {!! csrf_field() !!}
                        <button  type="submit" class="waves-effect waves-light btn">Mark as printed</button>
                    </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

@endsection




