@extends('layouts.app')

@section('content')

<div class="row mb-3 mt-3">
    <div class="col">
        <table class="table table-bordered">
            <tr>
                <td>
                    <span class="d-block text-muted small">PLATE NUMBER</span>
                    <span class="table-details">
                        @if($truck->plate_number == null)
                            {{  $truck->reg_number }}
                        @else
                            {{  $truck->plate_number }}
                        @endif 
                    </span>
                </td>
                <td>
                    
                    @if($truck->availability == 1)
                        <span class="badge badge-primary float-right mr-3">
                            ACTIVE
                        </span>
                    @else
                        <span class="badge badge-warning float-right">
                            INACTIVE
                        </span>
                    @endif
                    <span class="d-block text-muted small">DRIVER NAME</span>
                    <span class="table-details">
                        @if($truck->drivers->count() != 0)
                            {{ $truck->drivers->first()->name }}
                        @else
                            NO DRIVER
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <span class="d-block text-muted small text-uppercase">Subvendor</span>
                    <span class="table-details">
                        @if(!count($truck->haulers) == 0)
                            {{ $truck->hauler->name }}
                        @else
                            {{ $search->haulerName($truck->subvendor_description) }}
                        @endif
                    </span>
                </td>
                <td>
                    <span class="d-block text-muted small text-uppercase">Vendor</span>
                    <span class="table-details">
                        @if(!count($truck->haulers) == 0)
                                {{ $truck->hauler->name }}
                        @else
                            {{ $search->haulerName($truck->vendor_description) }}
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="d-block text-muted small text-uppercase">Truck Plant(s)</span>
                    <div class="row">
                        @if(!count($truck->plants) == 0)
                            @foreach($truck->plants->chunk(5) as $plant)
                                <div class="col">
                                    @foreach($plant as $item)
                                        <span class="badge badge-secondary">
                                                {{ $item->plant_name }}
                                        </span><br/>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                </td>
                <td>
                    <span class="d-block text-muted small text-uppercase">Truck Plant(s)</span>
                    <span class="table-details">
                        @if(!empty($truck->capacity))
                            {{ $truck->capacity->description }}
                        @endif
                    </span>

                </td>
            </tr>
        </table>
    </div>
</div>


    <div class="row">
        <div class="col">
            <form method="POST" action="{{ url('/inspects/deactivate/'.$truck->id) }}">
                {!! csrf_field() !!}
                    @include('inspects.form')
            </form>
        </div>
    </div>
          
                    

@endsection