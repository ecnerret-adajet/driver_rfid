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
@inject('search', 'App\Http\Controllers\PickupsController')


   <div class="card mx-auto mb-3">
        <div class="card-header">
        All Pickups

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        <a class="btn btn-primary btn-sm pull-right mr-2" href="{{ url('/pickups/create') }}">
        Add New Pickup
        </a>
        </div>
        <div class="card-body">


           <pickups></pickups>



            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#assigned" role="tab">Assigned</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#served" role="tab"> Served </a>
                    </li>

                     <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab"> Not Yet Served </a>
                    </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active pt-3" id="assigned" role="tabpanel">   

                           @include('pickups.assigned')

                </div><!-- end tab1 -->

                <div class="tab-pane pt-3" id="served" role="tabpanel">   
                             
                           @include('pickups.served')

                </div><!-- end tab1 -->
            
                <div class="tab-pane pt-3" id="profile" role="tabpanel">

                            @include('pickups.notYetServed')

                </div><!-- end tab 2 -->

            </div>
         

        </div><!-- end card-body -->
    </div> <!-- end card -->
            


            @foreach($assigned as $pick)

                <!-- Change availabitlity status to inactive -->
                <div class="modal fade bs-setInactive{{$pick->id}}-modal-lg">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Deactivate</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>  
                            Are you sure you want to proceed with this action?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="{{ url('/pickups/deactivate/'.$pick->id) }}">
                                    {!! csrf_field() !!}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button> 
                        </form>  
                    </div>
                    </div>
                </div>
                </div>


            @endforeach


           @foreach($unserved as $pick)
                        <!-- Assign RFID to pickup modal -->
                        <div class="modal fade assign-rfid-{{$pick->id}}">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Assign RFID</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {!! Form::model($pick, ['method' => 'PATCH','route' => ['pickups-assign.update', $pick->id], 'class' => 'bootstrap-modal-form', 'enctype'=>'multipart/form-data']) !!}
                            <div class="modal-body">
                            {!! csrf_field() !!}

                            <div class="row">
                                <div class="col">
                                    <small class="text-muted" style="text-transform:uppercase">
                                        Driver Name:
                                    </small><br/>
                                    {{ $pick->driver_name }}
                                </div>
                                <div class="col">
                                    <small class="text-muted" style="text-transform:uppercase">
                                        Plate Number:
                                    </small><br/>
                                    {{ $pick->plate_number }}
                                </div>
                                <div class="col">
                                    <small class="text-muted" style="text-transform:uppercase">
                                        Company:
                                    </small><br/>
                                    {{ $pick->company }}
                                </div>
                            </div>

                            <hr/>


                                    <div class="form-group row {{ $errors->has('cardholder_list') ? ' has-danger' : '' }}">
                                            <label class="col-md-2">RFID Card</label>
                                            <div class="col-md-10">
                                                {!! Form::select('cardholder_list', $search->cardholderAvailability(), null, ['placeholder' => 'Select Pickup Number', 'class' => 'form-control select2-pickup'] ) !!}
                                            @if ($errors->has('cardholder_list'))
                                                <div class="form-control-feedback">
                                                <small>
                                                    {{ $errors->first('cardholder_list') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm</button> 
                            </div>
                            {!! Form::close() !!} 
                            </div>
                        </div>
                        </div>
            @endforeach

    

@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
        $('#dataTable2').DataTable();
    });
</script>
@endsection




