
  <div class="form-group {{ $errors->has('user_list') ? ' has-danger' : '' }}">
        <div class="form-row">
            <div class="col-md-12">
             <label>Approvers Email</label>
             @if(count($setting->user) == 0)
            {{ Form::select('user_list', $users, null, ['class' => 'form-control', 'placeholder' => 'Select User']) }}
            @else
            {{ Form::select('user_list', $users, $setting->user->id, ['class' => 'form-control', 'placeholder' => 'Select User']) }}
            @endif
            @if ($errors->has('user_list'))
                <div class="form-control-feedback">
                    <small>
                    {{ $errors->first('user_list') }}
                    </small>
                </div>
            @endif
            </div> 
        </div>
    </div>

    <button type="submit"  class="btn btn-primary btn-block">Submit</button>
