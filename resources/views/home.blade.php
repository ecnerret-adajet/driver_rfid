@extends('layouts.app')
@section('content')

  <!-- Breadcrumbs -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">My Dashboard</li>
    </ol>

    <home></home>

    @if(\Entrust::hasRole('Administrator'))

        <dashboard></dashboard>

    @else

        <div class="row mt-4">
            <div class="col p-3 text-center">
                <p class="display-4 text-muted">
                    We Cannot Show the dashboard
                </p>
            </div>
        </div>
  
    @endif
  
@endsection

