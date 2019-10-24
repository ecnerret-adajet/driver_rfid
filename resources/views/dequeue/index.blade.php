@extends('layouts.app')

@section('content')

    <dequeues role="{{ Auth::user()->roles->first()->name }}"></dequeues>

@endsection


