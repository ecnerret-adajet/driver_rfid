<div class="had-container" style="padding-top: 50px;">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
        

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="textarea1" name="remarks" class="materialize-textarea"></textarea>
                            <label>Card Number</label>
                            @if ($errors->has('remarks'))
                            <span class="help-block">
                            <strong>{{ $errors->first('remarks') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            {{ Form::text('odometer', null, ['class' => 'validate']) }}
                            <label>Odomenter</label>
                            @if ($errors->has('odometer'))
                            <span class="help-block">
                            <strong>{{ $errors->first('odometer') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                     <div class="row">
                        <div class="input-field col s12">
                            {{ Form::select('location_list', $locations, $monitor->location_id, ['class' => 'validate', 'placeholder' => 'Select Location']) }}
                            <label>Location</label>
                            @if ($errors->has('location_list'))
                            <span class="help-block">
                            <strong>{{ $errors->first('location_list') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    
                     <div class="row">
                        <div class="input-field col s12">
                            {{ Form::select('status_list', $statuses, $monitor->status_id, ['class' => 'validate', 'placeholder' => 'Select Status']) }}
                            <label>Status</label>
                            @if ($errors->has('status_list'))
                            <span class="help-block">
                            <strong>{{ $errors->first('status_list') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s12">
                            {{ Form::select('duration_list', $durations, $monitor->duration_id, ['class' => 'validate', 'placeholder' => 'Select Duration']) }}
                            <label>Duration</label>
                            @if ($errors->has('duration_list'))
                            <span class="help-block">
                            <strong>{{ $errors->first('duration_list') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s12">
                            {{ Form::select('detail_list', $details, $monitor->detail_id, ['class' => 'validate', 'placeholder' => 'Select Truck Details']) }}
                            <label>Details</label>
                            @if ($errors->has('detail_list'))
                            <span class="help-block">
                            <strong>{{ $errors->first('detail_list') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                    <div class="row">
                        <button type="submit" class="btn waves-effect waves-light">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </div>

                </form>
            </div>
        </div>

</div>



@section('script')
    <script>
        $('select').material_select();
    </script>
@endsection