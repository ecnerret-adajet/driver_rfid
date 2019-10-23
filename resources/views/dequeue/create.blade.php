@extends('layouts.app')

@section('content')


<div class="content-header">
        <div class="row mb-2">
        <div class="col-6">
        <h1 class="m-0 text-dark">Dequeue a entry</h1>
        </div><!-- /.col -->
        <div class="col-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">{{ Carbon\Carbon::today()->format('M d, Y') }}</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

@endsection