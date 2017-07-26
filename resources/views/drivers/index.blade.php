@extends('layouts.app')

@section('content')

<drivers></drivers>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large waves-effect waves-light red" href="{{url('drivers/create')}}">
      <i class="material-icons">add</i>
  </a>
</div>
@endsection


