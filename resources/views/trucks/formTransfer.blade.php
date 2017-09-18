<div class="form-row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('vendor_description') ? ' has-danger' : '' }}">
                            <label>Vendor Number</label>
                            {!! Form::select('vendor_description', $haulers, $truck->vendor_description, ['placeholder' => 'Select Vendor','class' => 'form-control select2-vendor'] ) !!}
                            @if ($errors->has('vendor_description'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('vendor_description') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('subvendor_description') ? ' has-danger' : '' }}">
                            <label>Subvendor Number</label>
                            {!! Form::select('subvendor_description', $haulers_subcon,  $truck->subvendor_description, ['placeholder' => 'Select Subvendor', 'class' => 'form-control select2-subvendor'] ) !!}
                            @if ($errors->has('subvendor_description'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('subvendor_description') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('validity_start_date') ? ' has-danger' : '' }}">
                            <label>Start Validity Date</label>
                            {!! Form::input('date', 'validity_start_date', $truck->validity_start_date, ['class' => 'form-control'] ) !!}
                            @if ($errors->has('validity_start_date'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('validity_start_date') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('validity_end_date') ? ' has-danger' : '' }}">
                            <label>End Validity Date</label>
                            {!! Form::input('date', 'validity_end_date', $truck->validity_end_date, ['class' => 'form-control'] ) !!}
                            @if ($errors->has('validity_end_date'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('validity_end_date') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        
        
            <button type="submit"  class="btn btn-primary btn-block">Submit</button>
    
@section('script')
    <script>
        $(".select2-vendor").select2();
        $(".select2-subvendor").select2();
    </script>    
@endsection