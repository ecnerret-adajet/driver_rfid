@extends('layouts.app')

@section('top-script')
    <style>
        .help-block {
            color: red;
            margin-left: 150px;
        }
    </style>
@endsection

@section('content')

        <div class="card mx-auto mb-3">
        <div class="card-header">
            Booking Request - Entry

        <a class="btn btn-primary btn-sm pull-right" href="#">
            Save
        </a>

        </div>
        <div class="card-body">



       
             <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('plate_number') ? ' has-danger' : '' }}">
                            <label>Shipper's Name</label>
                            {{ Form::text('plate_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Shippers Name']) }}
                            @if ($errors->has('plate_number'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('plate_number') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
                
        


            
        
    </div>
    </div>


    <div class="card mx-auto mb-3">
        <div class="card-header">
            Booking Request Details

        </div>
        <div class="card-body">

        <div class="row">
            <div class="col-6">
                <ul class="list-group border-0 text-right text-muted">
                    <li class="list-group-item border-0">Order Reference</li>
                    <li class="list-group-item border-0">Order Reference No.</li>
                    <li class="list-group-item border-0">Booking Date</li>
                    <li class="list-group-item border-0">Consignee</li>
                    <li class="list-group-item border-0">Destination</li>
                    <li class="list-group-item border-0">Mode of Shipment</li>
                    <li class="list-group-item border-0">Plate Number</li>
                    <li class="list-group-item border-0">Driver</li>
                </ul>
            </div>
            <div class="col-6">
                <ul class="list-group border-0 text-left">
                    <li class="list-group-item border-0">STO</li>
                    <li class="list-group-item border-0">111222333444</li>
                    <li class="list-group-item border-0">April 13, 2020</li>
                    <li class="list-group-item border-0">PFMC</li>
                    <li class="list-group-item border-0">PALAWAN</li>
                    <li class="list-group-item border-0">Door to Door</li>
                    <li class="list-group-item border-0">ABJ-6592</li>
                    <li class="list-group-item border-0">Agapito Reyes</li>
                </ul>
            </div>
        </div>
                
        
    </div>
    </div>
        
@endsection
