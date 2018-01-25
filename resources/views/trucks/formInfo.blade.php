  

            

             <div class="form-row">

               <div class="col-md-6">
                    <div class="form-group {{ $errors->has('contract_list') ? ' has-danger' : '' }}">
                            <label>Contract Code</label>
                            {!! Form::select('contract_list', $contracts, null, ['class' => 'form-control select2-contract','placeholder' => 'Select Contract'] ) !!}
                            @if ($errors->has('contract_list'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('contract_list') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('capacity_list') ? ' has-danger' : '' }}">
                            <label>Capacity</label>
                            {!! Form::select('capacity_list', $capacities, $truck->capacity_id == null ? null : $truck->capacity_id, ['placeholder' => 'Select Capacity', 'class' => 'form-control select2-capacity'] ) !!}                           
                            @if ($errors->has('capacity_list'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('capacity_list') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
              
            </div>



            <div class="form-row">
                     <div class="col-md-12">
                    <div class="form-group {{ $errors->has('plant_list') ? ' has-danger' : '' }}">
                            <label>Plant Truck</label>
                            {!! Form::select('plant_list[]', $plants, null, ['placeholder' => 'Select Plant', 'class' => 'form-control select2-plant','multiple'] ) !!}
                            @if ($errors->has('plant_list'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('plant_list') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        
        
            <button type="submit"  class="btn btn-primary btn-block">Submit</button>
      

@section('script')
    <script>
        $(".select2-capacity").select2();

        $(".select2-contract").select2({
            placeholder: "Select Contract",
            allowClear: true
        });
        $(".select2-plant").select2();

    </script>
  
@endsection