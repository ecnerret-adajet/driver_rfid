    <div class="form-row">
                        
        <div class="col-12">
            <div class="form-group {{ $errors->has('plant_deactivated') ? ' has-danger' : '' }}">
                <label class="text-uppercase">Where to deactivate</label>

                @if(Request::is('inspects/deactivate*'))
                    {{ Form::select('plant_deactivated', $driverlocations, $truck->access_location, ['class' => 'form-control', 'placeholder' => 'Select Location']) }}
                    @if ($errors->has('plant_deactivated'))
                        <div class="form-control-feedback">
                            <small>
                            {{ $errors->first('plant_deactivated') }}
                            </small>
                        </div>
                    @endif
                @else
                    <input class="form-control " disabled value="Activates in all location">
                @endif

            </div>
        </div>

    </div>

    <div class="form-row my-3">
                        
        <div class="col-12">
            <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                <label class="text-uppercase">Remarks</label>
                {{ Form::textarea('remarks', null, ['class' => 'form-control', 'placeholder' => 'Enter Remarks','rows' => '3']) }}
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


    <button type="submit"  class="btn btn-primary px-5 py-3">Submit</button>