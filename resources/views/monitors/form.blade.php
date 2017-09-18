
    
    <div class="form-row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                <label>Remarks</label>
                {{ Form::textarea('remarks', $monitor->remarks, ['class' => 'form-control', 'placeholder' => 'Enter Remarks','rows' => '3']) }}
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


    <div class="form-row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('odometer') ? ' has-danger' : '' }}">
                <label>Odometer</label>
                {{ Form::number('odometer', $monitor->odometer, ['placeholder' => 'Enter Odometer', 'class' => 'form-control']) }}
                @if ($errors->has('odometer'))
                        <div class="form-control-feedback">
                            <small>
                            {{ $errors->first('odometer') }}
                            </small>
                        </div>
                    @endif
            </div>
        </div>
    </div>


     <div class="form-row">
               
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('location_list') ? ' has-danger' : '' }}">
        <label>Location</label>
        {{ Form::select('location_list', $locations, $monitor->location_id, ['class' => 'form-control', 'placeholder' => 'Select Location']) }}
        @if ($errors->has('location_list'))
                <div class="form-control-feedback">
                    <small>
                    {{ $errors->first('location_list') }}
                    </small>
                </div>
            @endif
        </div>
        </div>

        <div class="col-md-6">
            <div class="form-group {{ $errors->has('status_list') ? ' has-danger' : '' }}">
            <label>Status</label>
            {{ Form::select('status_list', $statuses, $monitor->status_id, ['class' => 'form-control', 'placeholder' => 'Select Status']) }}
            @if ($errors->has('status_list'))
                <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('status_list') }}
                        </small>
                    </div>
            @endif
            </div>
        </div>

    </div>


     <div class="form-row">
               
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('duration_list') ? ' has-danger' : '' }}">
        <label>Duration</label>
        {{ Form::select('duration_list', $durations, $monitor->duration_id, ['class' => 'form-control', 'placeholder' => 'Select Duration']) }}
        @if ($errors->has('duration_list'))
                <div class="form-control-feedback">
                    <small>
                    {{ $errors->first('duration_list') }}
                    </small>
                </div>
            @endif
        </div>
        </div>

        <div class="col-md-6">
            <div class="form-group {{ $errors->has('detail_list') ? ' has-danger' : '' }}">
            <label>Details</label>
            {{ Form::select('detail_list', $details, $monitor->detail_id, ['class' => 'form-control', 'placeholder' => 'Select Truck Details']) }}
            @if ($errors->has('detail_list'))
                <div class="form-control-feedback">
                        <small>
                        {{ $errors->first('detail_list') }}
                        </small>
                    </div>
            @endif
            </div>
        </div>

    </div>

    
<button type="submit"  class="btn btn-primary btn-block">Submit</button>

    
