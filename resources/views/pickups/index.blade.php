@extends('layouts.app')

@section('content')

<pickups></pickups>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large waves-effect waves-light red" href="{{url('pickups/create')}}">
      <i class="material-icons">add</i>
  </a>
</div>

@endsection



