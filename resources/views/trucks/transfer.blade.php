@extends('layouts.app')
@section('content')

       
    <div class="card mx-aut mb-3">
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
                     @if($truck->vendor_number == null)
                        @foreach($truck->haulers as $hauler)
                                {{ $hauler->name }}
                        @endforeach
                    @else
                        {{ $truck->vendor_number }}
                    @endif

                </div>

                <div class="col-sm-4">

                    <span class="text-muted">SUBVENDOR:</span><br/>
                    {{ $truck->subvendor_number }}
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


        </div><!-- end card-body -->
    </div> <!-- end card -->




                 <div class="card mx-auto mb-3">
                    <div class="card-header">
                        Transfer to 3PL

                    </div>
                    <div class="card-body">
                        


                    {!! Form::model($truck, ['method' => 'PATCH','route' => ['transfer.update', $truck->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                    <form>
                    @include('trucks.formTransfer')
                     </form>
                            
                    {!! Form::close() !!}
                            
                   

                
                </div>
                </div>



            


@endsection