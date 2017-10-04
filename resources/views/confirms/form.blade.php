
<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('status') ? ' has-danger' : '' }}">
                <label>Status</label>
                {!! Form::select('status', ['Approve' => 'Approve', 'Disapprove' => 'Disapprove'], null, ['placeholder' => 'Select Status','class' => 'form-control'] ) !!}
                
                @if ($errors->has('status'))
                    <div class="form-control-feedback">
                    <small>
                        {{ $errors->first('status') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>



<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                <label>Remarks</label>
                {!! Form::textarea('remarks', null, ['placeholder' => 'Enter Remarks','class' => 'form-control','rows' => '3'] ) !!}
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


@section('script')
    <script>
        $(".select2-status").select2({

        });
    </script>
@endsection