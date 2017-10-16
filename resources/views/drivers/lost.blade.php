@extends('layouts.app')
@section('content')

 
     <div class="card mx-auto mb-3">
        <div class="card-header">
        Lost RFID Card

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
    
          {!! Form::model($driver, ['method' => 'PATCH','url' => 'drivers/lostCard/'.$driver->id, 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
         
          {!! csrf_field() !!}
          <form>

            <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('card_list') ? ' has-danger' : '' }}">
                                <label>New RFID Card </label>
                        {!! Form::select('card_list', $cards, count($driver->card) == 0 ? 'null' : $driver->card->CardID, ['placeholder' => 'Select Deploy RFID',  'class' => 'form-control select2-card'] ) !!}
                                @if ($errors->has('card_list'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('card_list') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
            </div>

            <a class="btn btn-primary btn-block" href="javascript:void(0);" data-toggle="modal" data-target="#lostCardModal">
                Submit
            </a>

    <!-- Lost Card Modal -->
    <div class="modal fade" id="lostCardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                By clicking the "confirm" button. You have agreed to proceed with assigning new card to a driver    
            </em>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

            <button type="submit"  class="btn btn-primary">Confirm</button>
          
          </div>
        </div>
      </div>
    </div>

          </form>
          {!! Form::close() !!}     

     

        </div>
      </div> 







@endsection
@section('script')
<script>
  $(".select2-card").select2({
             placeholder: "Select Card",
            allowClear: true,
        });
</script>
@endsection