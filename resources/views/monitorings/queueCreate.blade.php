@extends('layouts.app')
@section('content')

   <div class="card mx-auto mb-3">
        <div class="card-header">
        Entries Monitoring
        </div>
        <div class="card-body">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

            <li class="nav-item">
            <a class="nav-link " href="{{ url('gates/create') }}" role="tab">Gate Entries</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="#" role="tab">Queues Entries</a>
            </li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            <div class="tab-pane active" id="gate" role="tabpanel">
                 {!! Form::model($gate = new \App\Gate, ['url' => 'gates/store', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
                    <div class="form-row mb-1 mt-3">
                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Title</label>
                                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                @if ($errors->has('title'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('title') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Location</label>
                                {{ Form::select('area_list', $areas, null, ['placeholder' => 'Select Plant Location','class' => 'p-2 form-control select2-area']) }}
                                @if ($errors->has('area_list'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('area_list') }}
                                        </small>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Door ID</label>
                                {{ Form::number('door', null, ['class' => 'form-control', 'placeholder' => 'Enter Door ID']) }}
                                @if ($errors->has('door'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('door') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Controller ID</label>
                                {{ Form::number('controller', null, ['class' => 'form-control', 'placeholder' => 'Enter Controller ID']) }}
                                @if ($errors->has('controller'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('controller') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-block btn-primary" type="submit">Generate</button>
                            </div>
                        </div>
                    </div> <!-- end row -->
                    {!! Form::close() !!}

                    <gate-entries></gate-entries>
            </div><!-- end tab -->
        
            <div class="tab-pane" id="queues" role="tabpanel">
                {!! Form::model($driverqueue = new \App\Driverqueue, ['url' => 'queues/store', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
                    <div class="form-row mb-3 mt-3">
                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Title</label>
                                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                @if ($errors->has('title'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('title') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Location</label>
                                {{ Form::select('area_list', $areas, null, ['placeholder' => 'Select Plant Location','class' => 'p-2 form-control select2-area']) }}
                                @if ($errors->has('area_list'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('area_list') }}
                                        </small>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Gate Area</label>
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

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Door ID</label>
                                {{ Form::number('door', null, ['class' => 'form-control', 'placeholder' => 'Enter Door ID']) }}
                                @if ($errors->has('door'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('door') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Controller ID</label>
                                {{ Form::number('controller', null, ['class' => 'form-control', 'placeholder' => 'Enter Controller ID']) }}
                                @if ($errors->has('controller'))
                                    <div class="form-control-feedback text-danger">
                                        <small>
                                        {{ $errors->first('controller') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-muted text-uppercase" >Truckscale out ID</label>
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
                    </div> <!-- end row -->
                    <div class="form-row mb-3">
                         <div class="col">
                            <div class="form-group">
                                <button class="btn btn-block btn-primary" type="submit">Generate</button>
                            </div>
                        </div>
                    </div> <!-- end row -->

                    {!! Form::close() !!}

                    <driverqueue-entries></driverqueue-entries>
                    
                </div><!-- end tab -->


        </div>

             
        
        </div><!-- end card-body -->
    </div> <!-- end card -->





@endsection