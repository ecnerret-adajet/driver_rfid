@extends('layouts.app')

@section('content')

<haulers></haulers>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large waves-effect waves-light red" href="{{url('haulers/create')}}">
      <i class="material-icons">add</i>
  </a>
</div>

@endsection


