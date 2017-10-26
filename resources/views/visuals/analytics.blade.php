@extends('layouts.app')

@section('content')



   <div class="card mx-auto mb-3">
        <div class="card-header">
       Graphs
       
        </div>
        <div class="card-body">

            
            <graph :labels="['Drivers','Trucks']" :values="[{{ $stats['driver'] }}, {{ $stats['truck'] }}]"></graph>
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


    
    <dates></dates>


   {{--  <div class="card mx-auto mb-3">
        <div class="card-header">
       Top Haulers
       
        </div>
        <div class="card-body">
            
            <top :labels="{{ $label }}" :values="{{ $value }}"></top>

        </div><!-- end card-body -->
    </div> <!-- end card -->  --}}




@endsection