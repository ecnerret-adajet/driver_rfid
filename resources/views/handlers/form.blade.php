        <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('lfug_server') ? ' has-danger' : '' }}">
                            <label>LFUG</label>
                            {!! Form::select('lfug_server', $lfug, null, ['placeholder' => 'Select Capacity', 'class' => 'form-control select2-lfug'] ) !!}
                            @if ($errors->has('lfug_server'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('lfug_server') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('pfmc_server') ? ' has-danger' : '' }}">
                            <label>PFMC</label>
                            {!! Form::select('pfmc_server', $pfmc, null, ['placeholder' => 'Select Contract',  'class' => 'form-control select2-pfmc'] ) !!}
                            @if ($errors->has('pfmc_server'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('pfmc_server') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
            </div>

                         <button type="submit"  class="btn btn-primary btn-block">Submit</button>


@section('script')
    <script>
        $(".select2-lfug").select2({
             placeholder: "Select Vendor Number",
            allowClear: true,
        });

        $(".select2-pfmc").select2({
            placeholder: "Select Vendor Number",
            allowClear: true,
        });
    </script>
@endsection