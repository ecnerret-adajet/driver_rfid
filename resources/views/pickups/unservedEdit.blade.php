@extends('layouts.online')
@section('content')

     <div class="card mx-auto mb-3">
        <div class="card-header">
         Edit Pickup

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">        

            {!! Form::model($pickup, ['method' => 'PATCH','route' => ['pickups-unserved.update', $pickup->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}

            <form>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('plate_number') ? ' has-danger' : '' }}">
                                        <label>Plate Number</label>
                                        {{ Form::text('plate_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Plate Number']) }}
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


                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('driver_name') ? ' has-danger' : '' }}">
                                        <label>Driver Name</label>
                                        {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Driver Name']) }}
                                        @if ($errors->has('driver_name'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('driver_name') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('company') ? ' has-danger' : '' }}">
                                        <label>Company</label>
                                        {{ Form::text('company', null, ['class' => 'form-control', 'placeholder' => 'Enter Company']) }}
                                        @if ($errors->has('company'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('company') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('do_number') ? ' has-danger' : '' }}">
                                        <label>DO Details</label></label>
                                        {{ Form::text('do_number', null, ['class' => 'form-control', 'placeholder' => 'Enter DO Number']) }}
                                        @if ($errors->has('do_number'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('do_number') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                                        <label>Update Reason</label>


                                
                                        <select class="custom-select form-control" name="remarks" id="inputGroupSelect01">
                                            <option value="" selected>Choose...</option>
                                            <option value="changed plate number">Changed Plate Number</option>
                                            <option value="changed driver">Changed Driver</option>
                                            <option value="change / add do number">Changed / Add DO Number</option>
                                            <option value="typo error">Typo Error</option>
                                        </select>

                                        @if ($errors->has('remarks'))
                                            <div class="form-control-feedback">
                                                <small>
                                                {{ $errors->first('remarks') }}
                                                </small>
                                            </div>
                                        @endif
                                    
                                    </div>
                                </div>
                            </div>


                            <button type="submit"  class="btn btn-primary float-right">Submit</button>





             </form>

            {!! Form::close() !!}       
            
     
        </div>
      </div>

@endsection