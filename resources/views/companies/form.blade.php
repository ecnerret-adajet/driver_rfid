<div class="form-row">


                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
                        <label for="companyName">Company Name</label>
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'companyName', 'placeholder' => 'Enter Company Name']) }}
                        @if ($errors->has('name'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('name') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                        <label for="remarks">Remarks</label>
                        {{ Form::text('remarks', null, ['class' => 'form-control', 'id' => 'remarks', 'placeholder' => 'Remarks']) }}
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
