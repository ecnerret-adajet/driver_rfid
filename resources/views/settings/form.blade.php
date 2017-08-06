

<div class="had-container" style="padding-top: 50px;">
    <div class="col m12">
        <div class="card-panel grey lighten-4">
     
                <form class="col s12">


                    <div class="row">
                       <div class="input-field col s12">
                       @if(count($setting->user) == 0)
                        {{ Form::select('user_list', $users, null, ['class' => 'validate', 'placeholder' => 'Select User']) }}
                       @else
                        {{ Form::select('user_list', $users, $setting->user->id, ['class' => 'validate', 'placeholder' => 'Select User']) }}
                       @endif
                        <label>Emailing User</label>
                        @if ($errors->has('clasification_list'))
                            <span class="help-block red-text">
                                <strong>{{ $errors->first('clasification_list') }}</strong>
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
    </div> <!-- end col m12 -->
</div>


@section('script')
    <script>
        $('select').material_select();
    </script>
@endsection