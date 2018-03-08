@extends('layouts.app')
@section('content')
@inject('search', 'App\Http\Controllers\TrucksController')
         

        <div class="card mx-auto mb-3">
        <div class="card-header">
            Truck's Information

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">


             <div class="row">

                <div class="col-sm-2">

                       <span class="fa-stack fa-lg display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                        </span>               
                
                </div>

                <div class="col-sm-4">
                    <span class="text-muted">PLATE NUMBER:</span><br/>
                        @if($truck->plate_number == null)
                            {{  $truck->reg_number }}
                        @else
                            {{  $truck->plate_number }}
                        @endif                        
                    <br/> 
                    <br/>
                    <span class="text-muted">DRIVER NAME:</span><br/>
                        @forelse($truck->drivers as $driver)
                            {{$driver->name}}
                        @empty
                            <em style="color: red">NO DRIVER</em>
                        @endforelse
                    <br/>
                    <br/>
                    <span class="text-muted">VENDOR:</span><br/>
                        @if(!empty($truck->vendor_description))
                        {{ $search->haulerName($truck->vendor_description) }}
                        @endif
                </div>

                <div class="col-sm-4">

                    <span class="text-muted">SUBVENDOR:</span><br/>
                        @if(!empty($truck->hauler))
                        {{ $truck->hauler->name }}
                        @else
                        {{ $search->haulerName($truck->subvendor_description) }}
                        @endif
                    
                    <br/>
                    <br/>
                    <span class="text-muted">START VALIDITY DATE</span><br/>
                    {{ date('F, d Y',strtotime($truck->validity_start_date)) }}
                    <br/>
                    <br/>
                    <span class="text-muted">END VALIDITY DATE</span><br/>
                    {{ date('F, d Y',strtotime($truck->validity_end_date)) }}

             
                
                </div>
                <div class="col-sm-2">
                    <span class="text-muted">STATUS</span><br/>
                        @if($truck->availability == 1)
                            <span class="badge badge-primary">
                                ACTIVE
                            </span>
                        @else
                            <span class="badge badge-warning">
                                INACTIVE
                            </span>
                        @endif
                    <br/>
                    <br/>
                    <span class="text-muted">SUPPORTING DOCUMENT</span><br/>
                    @if($truck->documents != null)
                    <a class="btn btn-sm btn-outline-primary mt-2" href="{{url('/storage/'. $truck->documents)}}">                      
                        View Document
                    </a>
                    @else
                    <span class="text-muted">
                        <em>
                            No Document Found
                        </em>
                    </span>
                    @endif

                </div>  

            </div>

            
        
    </div>
    </div>

    <div class="card mx-auto mb-3">
        <div class="card-header">
            Truck's History

        </div>
        <div class="card-body">

        

        <table class="table">
        <thead>
            <tr>
                <th>Log ID</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Log Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($truck->inspections as $inspection)
            <tr>
            <td>
                {{ $inspection->id }}
            </td>
            <td>
                {{$inspection->remarks}}
            </td>
            <td>
             @if($inspection->status == 1)
                <button class="btn btn-sm btn-outline-success disabled">
                    ACTIVE
                </button>
             @else 
                <button class="btn btn-sm btn-outline-danger disabled">
                    INACTIVE
                </button>
             @endif
            </td>
            <td>
                {{ date('m/d/Y h:m:s A', strtotime($inspection->created_at))}}
            </td>
            </tr>
        @endforeach
        </tbody>
        </table>
            
        

        
    </div>
    </div>



            


@endsection