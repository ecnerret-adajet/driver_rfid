@extends('layouts.app')

@section('content')

<div class="row">
  
  
  <div class="input-group search pull-right">
    <span class="input-group-addon opener">
      <i class="material-icons">search</i>
    </span>
    <input type="text" class="form-control" placeholder="Search">
    <span class="input-group-addon">
      <i class="material-icons">more_vert</i>
    </span>
    <span class="input-group-addon opener">
      <i class="material-icons">clear</i>
    </span>
  
  </div>
 
  

</div>

<div class="had-container">

{{$drivers->count()}}

<ul class="collection">

    <li class="collection-item avatar">
      <i class="material-icons circle">folder</i>
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle green">insert_chart</i>
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle red">play_arrow</i>
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
  </ul>
      

</div>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large waves-effect waves-light red" href="{{url('drivers/create')}}">
      <i class="material-icons">add</i>
  </a>
</div>
@endsection

