@extends('layouts.app')
@section('content')
@inject('version_vendor', 'App\Http\Controllers\TrucksController')
         

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
                        @foreach($truck->drivers as $driver)
                            {{$driver->name}}
                        @endforeach
                    <br/>
                    <br/>
                    <span class="text-muted">VENDOR:</span><br/>
                        {{ $truck->vendor_description }}

                </div>

                <div class="col-sm-4">

                    <span class="text-muted">SUBVENDOR:</span><br/>
                        @foreach($truck->haulers as $hauler)
                            {{ $hauler->name }}
                        @endforeach
                        {{--  @foreach($subcon->where('id',$truck->subvendor_description)->take(1) as $x)
                                {{ $x->name }}
                        @endforeach  --}}
                    
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
                <th>Plate #</th>
                <th>Reg #</th>
                <th>Vendor</th>
                <th>Subvendor</th>
                <th>Base</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($versions as $version)
            <tr>
            <td>
                {{$version->plate_number}}
            </td>
            <td>
                {{$version->reg_number}}
            </td>
            <td>
               {{ $version_vendor->versionVendorName($version->vendor_description)['vendor_name'] }}
            </td>
            <td>
                 {{ $version_vendor->versionVendorName($version->subvendor_description)['vendor_name'] }}
            </td>
            <td>{{$version->base_id}}</td>
            <td>
                {{ date('m/d/Y', strtotime($version->start_validity_date))}}
            </td>
            <td>
                {{ date('m/d/Y', strtotime($version->end_validity_date))}}
            </td>
            </tr>
        @endforeach
        </tbody>
        </table>
            
        

        
    </div>
    </div>



            


@endsection