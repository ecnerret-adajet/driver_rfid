    <div class="form-row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                <label>Remarks</label>
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

    <button type="submit"  class="btn btn-primary btn-block">Submit</button>
