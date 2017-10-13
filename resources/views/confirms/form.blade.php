
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

<a class="btn btn-primary btn-block" href="javascript:void(0);" data-toggle="modal" data-target="#submitModal">
    Submit
</a>



        <!-- Logout Modal -->
    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Terms and condition</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center p-3">
            <em>
                By clicking the "confirm" button. You have reviewed to agree or disagree to the details presented from the driver's information            
            </em>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

            <button type="submit"  class="btn btn-primary">Confirm</button>
          
          </div>
        </div>
      </div>
    </div>


@section('script')
    <script>
        $(".select2-status").select2({

        });
    </script>
@endsection