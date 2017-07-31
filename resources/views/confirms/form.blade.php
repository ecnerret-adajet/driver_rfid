

<div class="had-container" style="padding-top: 50px;">   
    <div class="card-panel grey lighten-4">
        <div class="row">
            <form class="col s12">
    
                <div class="row">
                      <div class="input-field col s12">
                        <select name="status">
                            <option value="" disabled selected>Choose your option</option>
                            <option value="Approve">Approve</option>
                            <option value="Disapproved">Disapproved</option>
                        </select>
                        <label>Select Status</label>
                         @if ($errors->has('status'))
                            <span class="help-block red-text">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

               <div class="row">
                    <form class="col s12">
                        <div class="row">
                        <div class="input-field col s12">
                            {{Form::textarea('remarks', null, ['class' => 'materialize-textarea', 'data-length' => '120'])}}
                            <label>Remarks</label>
                        </div>
                        </div>
                    </form>
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