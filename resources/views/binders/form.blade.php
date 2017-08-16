      
    
<div class="col s12">
    <div class="card-panel grey lighten-4">
        <div class="row">
            <form class="col s12">

                <div class="row">
                    <div class="input-field col s12">
                        {{ Form::text('CardNo', $card->CardNo, ['class' => 'validate','disabled']) }}
                        <label>RFID Code</label>
                        @if ($errors->has('CardNo'))
                            <span class="help-block red-text">
                                <strong>{{ $errors->first('CardNo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                
                    <div class="row">
                    <div class="input-field col s12">
                    {!! Form::select('cardholder_list', $cardholders, $card->CardholderID, ['placeholder' => 'Select Deploy RFID', 'id' => 'select2-materialize-card', 'class' => 'validate'] ) !!}                            
                        <label>Cardholder Name</label>
                        @if ($errors->has('card_list'))
                            <span class="help-block red-text">
                            <strong>{{ $errors->first('card_list') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                
                <div class="row">
                    <div class="input-field col s12">
                            {{Form::select('rfid_list', $rfids, empty($binder) ? '' : $binder->rfid_id, ['placeholder' => 'Select Rfid Type', 'id' => 'select2-materialize-rfid', 'class' => 'validate'])}}
                            <label>RFID Type</label> 
                        @if ($errors->has('rfid_list'))
                            <span class="help-block red-text">
                                <strong>{{ $errors->first('rfid_list') }}</strong>
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