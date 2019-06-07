@extends('layouts.app')

@section('content')

    <replacements role="{{ Auth::user()->roles->first()->name }}"></replacements>

@endsection


