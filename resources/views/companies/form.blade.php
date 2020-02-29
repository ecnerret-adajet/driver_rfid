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



              </div>

    <button type="submit"  class="btn btn-primary btn-block">Submit</button>
