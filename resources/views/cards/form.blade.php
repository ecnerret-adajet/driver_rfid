

<div class="had-container" style="padding-top: 50px;">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">
        

                    <div class="row">
                        <div class="input-field col s12">
                            {{ Form::text('Name', $card->CardNo, ['class' => 'validate','disabled']) }}
                            <label>Card Number</label>
                        </div>
                    </div>

                    <div class="row">
                       <div class="input-field col s12">
                             {{Form::select('rfid_list', $rfids, null, ['id' => 'select2-materialize-cardholder', 'class' => 'validate'])}}
                            {{--  <label>Operator</label>  --}}
                            @if ($errors->has('rfid_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('cardholder_list') }}</strong>
                                </span>
                            @endif
                        </div> 
                    </div>

                    <div class="row">
                       <div class="input-field col s12">
                             {{Form::select('cardholder_list', $cardholders, null, ['id' => 'select2-materialize-cardholder', 'class' => 'validate'])}}
                            {{--  <label>Operator</label>  --}}
                            @if ($errors->has('cardholder_list'))
                                <span class="help-block red-text">
                                    <strong>{{ $errors->first('cardholder_list') }}</strong>
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
        $("#select2-materialize-cardholder").select2({
            placeholder: "Select Cardholder",
            allowClear: true,
        });

         $("#select2-materialize-rfid").select2({
            placeholder: "Select Cardholder",
            allowClear: true,
        });
    </script>
@endsection