@extends('layouts.app')

@section('content')
@inject('inject', 'App\Http\Controllers\DriversController')


<div class="row">
   
    <div class="col">
        <ul class="list-group list-group-flush">
            <li class="list-group-item text-center">
                @if(!empty($driver->image))
                    <img class="img-responsive rounded-circle border" style="height: 250px; width: auto;" src="{{asset('/storage/'. $driver->image->avatar)}}">
                @else
                    <img class="img-responsive rounded-circle border" style="height: 250px; width: auto;" src="{{asset('/storage/'. $driver->avatar)}}">
                @endif
            </li>
            <li class="list-group-item border-0">
                
                <div class="header-lines">
                    <span class="text-muted text-uppercase font-weight-bold">
                        Information
                    </span>  
                </div>

                <p class=" text-muted font-weight-bold mb-0 pb-0 mt-4" style="font-size: 1.2em">
                    Address:
                </p>
                <span class="{{ isset($driver->address) ?: 'text-muted' }}">
                    {{ isset($driver->address) ? $driver->address : 'NOT PROVIDED' }}
                </span>

                <p class=" text-muted font-weight-bold mb-0 pb-0 mt-4" style="font-size: 1.2em">
                    Nbi Number:
                </p>
                <span class="{{ isset($driver->nbi_number) ?: 'text-muted' }}">
                    {{ isset($driver->nbi_number) ? $driver->nbi_number : 'NOT PROVIDED' }}
                </span>

                <p class=" text-muted font-weight-bold mb-0 pb-0 mt-4" style="font-size: 1.2em">
                    License Number:
                </p>
                <span class="{{ isset($driver->driver_license) ?: 'text-muted' }}">
                    {{ isset($driver->driver_license) ? $driver->driver_license : 'NOT PROVIDED' }}
                </span>

                 <p class=" text-muted font-weight-bold mb-0 pb-0 mt-4" style="font-size: 1.2em">
                    Contact Person:
                </p>
                <span class="{{ isset($driver->contact_person) ?: 'text-muted' }}">
                    {{ isset($driver->contact_person) ? $driver->contact_person : 'NOT PROVIDED' }} {{ isset($driver->contact_phone) ? '/ '.$driver->contact_phone : '' }} 
                </span>

                <div class="header-lines mt-4">
                    <span class="text-muted text-uppercase font-weight-bold">
                        Entry Details
                    </span>  
                </div>

                 <p class=" text-muted font-weight-bold mb-0 pb-0 mt-4" style="font-size: 1.2em">
                    Bind RFIDs: 
                </p>

                    @forelse ($cardholders as $cardholder)
                    <span class="badge badge-pill badge-primary">
                       {{ $cardholder->AccessGroupID }} - {{ $cardholder->CardNo  }} 
                    </span>
                    @empty
                    <span class="text-muted">
                        NO RFIDs
                    </span>
                    @endforelse

            </li>
            {{-- <li class="list-group-item border-0">Morbi leo risus</li>
            <li class="list-group-item">Porta ac consectetur ac</li>
            <li class="list-group-item">Vestibulum at eros</li> --}}
        </ul>

    </div>

     <div class="col-9">

         <div class="row mt-3">
             <div class="col">
                <p class="display-4 mb-0 font-weight-bold"> {{ ucwords(strtolower($driver->name)) }}</p>
                <span class="font-weight-light"  style="font-size: 1.4em;">
                    {{  isset($driver->hauler) ? $driver->hauler->name : 'NO HAULER' }}
                </span>
             </div>
         </div>

         <div class="row mt-4">
             <div class="col-2">
                 <span class="small text-uppercase text-muted">
                     Plate Number:
                 </span>
                <p style="font-size: 1.6em;" class="mt-0 pt-0 {{ isset($driver->truck) ? '' : 'text-muted' }}">
                    @if(!empty($driver->truck))
                        {{ $driver->truck->plate_number }}
                    @else
                        NO TRUCK
                    @endif
                 </p>
             </div>
             <div class="col-2">
                 <span class="small text-uppercase text-muted">
                     Capacity:
                 </span>
                 <p style="font-size: 1.6em;" class="mt-0 pt-0 {{ isset($driver->truck->capacity) ? '' : 'text-muted' }}">
                    @if(!empty($driver->truck))
                        {{ $driver->truck->capacity->description }}
                    @else
                        N/A
                    @endif
                 </p>
             </div>
         </div>

         <div class="row mt-3">
             <div class="col">
                 <a href="javascript:void(0);" class="btn btn-outline-primary rounded-0" data-toggle="modal" data-target="#driverPhone">
                    <i class="fa fa-phone"></i> Call Driver
                 </a>

                  <button  class="btn btn-outline-success rounded-0 ml-3">
                    <i class="fa fa-card"></i> Cardholder: {{ $driver->cardholder->Name }}
                  </button>
             </div>
         </div>


        <div class="row mt-4">
            <div class="col">
                   <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#raw_logs" role="tab">Raw Logs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#shipments_logs" role="tab">Shipment Logs</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#history_logs" role="tab">History Logs</a>
                    </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="raw_logs" role="tabpanel">
                    <driver-logs-parent driver_id="{{ $driver->id }}"></driver-logs-parent>
                </div>
            
                <div class="tab-pane pt-3" id="shipments_logs" role="tabpanel">
                    <shipments driver="{{ $driver->id }}"></shipments>
                </div>


                <div class="tab-pane pt-3" id="history_logs" role="tabpanel">
                    <driver-history driver="{{ $driver->id}}"></driver-history>
                </div>

            </div>
            </div>
        </div>


    </div>
</div>



 <!-- Modal -->
    <div class="modal fade" id="driverPhone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Driver Number</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    {{ $driver->phone_number }}
                </div>
            </div>
        </div>
        </div>
    </div>
</div>



@endsection


