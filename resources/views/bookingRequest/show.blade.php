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

        <button id="update-book-request" class="btn btn-primary btn-sm pull-right" href="#">
            Save
        </button>

        </div>
        <div class="card-body">

        {!! Form::model($bookingRequest, ['id'=>'bookingRequestUpdate', 'method' => 'PATCH','route' => ['booking-requests.update', $bookingRequest->id], 'enctype'=>'multipart/form-data']) !!}
        {!! csrf_field() !!}
        <form>
        <div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
            <label class="col-md-3 col-form-label">
                Shipper's Name
            </label>
            <div class="col-md-9">
                {{ Form::text('shippers_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Shippers Name']) }}
            </div>
        </div>

        <div class="form-group row {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
            <label class="col-md-3 col-form-label">
                Driver Name
            </label>
            <div class="col-md-9">
                {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Driver Name']) }}
            </div>
        </div>

        <div class="form-group row {{ $errors->has('plate_number') ? ' has-danger' : '' }}">
                <label class="col-md-3 col-form-label">
                        Plate Number
                </label>
                    <div class="col-md-9">
                {{ Form::text('plate_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Plate Number']) }}
            </div>
        </div>
        </form>
        {!! Form::close() !!}
        
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
                    <li class="list-group-item border-0">Ship Type</li>
                    <li class="list-group-item border-0">Mode of Shipment</li>
                    <li class="list-group-item border-0">Plate Number</li>
                    <li class="list-group-item border-0">Driver</li>
                </ul>
            </div>
            <div class="col-6">
                <ul class="list-group border-0 text-left">
                    <li class="list-group-item border-0">{{ $bookingRequest->order_reference }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->order_reference_no }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->booking_date }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->consignee }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->destination }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->ship_type }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->mode_of_shipment }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->plate_number }}</li>
                    <li class="list-group-item border-0">{{ $bookingRequest->driver_name }}</li>
                </ul>
            </div>
        </div>
                
        
    </div>
    </div>
        
@endsection

@section('script')
    <script>
        $(function(){
            $('#update-book-request').on('click', function(){
                $('#bookingRequestUpdate').submit();
            });
            })
    </script>
@endsection
