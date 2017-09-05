@extends('layouts.app')

@section('content')

<trucks></trucks>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large waves-effect waves-light red" href="{{url('trucks/create')}}">
      <i class="material-icons">add</i>
  </a>
</div>
@endsection



