@extends('layouts.app')

@section('content')


 <div class="card mx-auto mb-3">
        <div class="card-header">
        Pick List

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
       
        </div>
        <div class="card-body">

     
        
<div class="row mb-3">
    <div class="col-sm-12">
        <h4 class="pb-0 mb-0">Picking Request</h4>
        <h5 class="pt-0 mt-0">Shipment No.</h5>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">

            Shipping Point <br/>
            Plant / Store <br/>

    </div>
</div>

<div class="row">
    <div class="col-sm-6">

            Picking Date: <br/>
            Loading Date: <br/>
            Route:

    </div>

    <div class="col-sm-6">

        Trucking No: <br/>
        Truck Driver: <strong> {{ $driver->name }} </strong> <br/>
        Truck Plate#: <strong> {{ $driver->truck->plate_number }} </strong>  <br/>
        Hauler / Vendor:  <strong> {{ $driver->hauler->name }} </strong>
    
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        Ship-to-Party:
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12">
    
        Truck Tare Weight: <br/>
        Product Gross Weight: <br/>
        Product Net Weight: <br/>
        Check-In Date: <br/>
        Check-In Time: <br/>
    
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>DO</th>
                        <th>Item</th>
                        <th>Material</th>
                        <th>Description</th>
                        <th>Batch</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                        <th>Sales Unit</th>
                        <th>Base Unit</th>
                    </tr>
                </thead>
                <tbody>
                                    
                </tbody>
            </table>

    </div>
</div>


<div class="row mt-5 text-center">
    <div class="col-sm-4">
        <span>Prepared By</span>
    </div>

    <div class="col-sm-4">
        <span>Received By</span>
    </div>

    <div class="col-sm-4">
        <span>Warehouse Checker</span>
    </div>
</div>



        
        </div><!-- end card-body -->
    </div> <!-- end card -->



@endsection