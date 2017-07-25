

<div class="row" style="padding-top: 50px;">
    <div class="col m8 offset-m2">
        <div class="card-panel grey lighten-4">
            <div class="row">
                <form class="col s12">

                    <div class="row">
                       <div class="input-field col s12">
                        <select>
                            <option value="" disabled selected>Choose your option</option>
                            <option value="1">AAA 000</option>
                            <option value="2">AAA 001</option>
                            <option value="3">AAA 002</option>
                        </select>
                        <label>Plate Number</label>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            {{ Form::text('name', null, ['class' => 'validate']) }}
                            <label>Driver Name</label>
                        </div>

                        <div class="input-field col s6">
                            {{Form::select('hauler_list', $haulers, null, ['class' => 'validates', 'placeholder' => 'Select Operator'])}}
                            <label>Operator</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            {{Form::text('phone_number', null, ['class' => 'validate'])}}
                            <label>Phone Number</label>
                        </div>

                        <div class="input-field col s6">
                            {{Form::text('substitute', null, ['class' => 'validate'])}}
                            <label>Substitute</label>
                        </div>
                    </div>


                    <div class="row">
                        <a class="btn waves-effect waves-light" href="#">Submit
                            <i class="material-icons right">send</i>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@section('script')
    <script>
        $('select').material_select();
    </script>
@endsection