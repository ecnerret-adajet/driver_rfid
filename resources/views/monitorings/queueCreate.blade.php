@extends('layouts.app')
@section('content')

            <div class="row mb-3">

                <div class="col-sm-12">

                 <div class="card mx-auto">
                    <div class="card-header">
                        Add New Queue Monitoring

                        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                        Back
                        </a>
                    </div>
                    <div class="card-body" id="form-truck">
                      

                        {!! Form::model($driverqueue = new \App\Driverqueue, ['url' => 'queues/store', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                        {!! csrf_field() !!}
                      
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('title') ? ' has-danger' : '' }}">
                                             <label>Title</label>
                                            {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                            @if ($errors->has('title'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('title') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('area_list') ? ' has-danger' : '' }}">
                                             <label>Queue Area</label>
                                            {{ Form::select('area_list', $areas, null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                            @if ($errors->has('area_list'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('area_list') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('gate_list') ? ' has-danger' : '' }}">
                                             <label>Gate Area</label>
                                            {{ Form::select('gate_list', $gates, null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                            @if ($errors->has('gate_list'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('gate_list') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('door') ? ' has-danger' : '' }}">
                                        <label>Door ID</label>
                                        {{ Form::number('door', null, ['class' => 'form-control', 'placeholder' => 'Enter Door ID']) }}
                                        @if ($errors->has('door'))
                                            <div class="form-control-feedback">
                                                <small>
                                                {{ $errors->first('door') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('controller') ? ' has-danger' : '' }}">
                                        <label>Controller ID</label>
                                        {{ Form::number('controller', null, ['class' => 'form-control', 'placeholder' => 'Enter Controller ID']) }}
                                        @if ($errors->has('controller'))
                                            <div class="form-control-feedback">
                                                <small>
                                                {{ $errors->first('controller') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('ts_out_controller') ? ' has-danger' : '' }}">
                                        <label>Truckscale Out ID</label>
                                        {{ Form::number('ts_out_controller', null, ['class' => 'form-control', 'placeholder' => 'Enter Controller ID']) }}
                                        @if ($errors->has('ts_out_controller'))
                                            <div class="form-control-feedback">
                                                <small>
                                                {{ $errors->first('ts_out_controller') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <button type="submit"  class="btn btn-primary btn-block p-2">PUBLISH</button>
                    
                        {!! Form::close() !!}

                  
                </div>
                </div>



                </div>

                
            </div><!-- end row -->


@endsection