@inject('search', 'App\Http\Controllers\TrucksController')
  
             @if(Request::is('trucks/create'))
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('plate_number') ? ' has-danger' : '' }}">
                            <label>Plate Number</label>
                            {{ Form::text('plate_number', null, ['class' => 'form-control', 'id' => 'sPriceRewards', 'placeholder' => 'Enter Plate Number', "data-inputmask" => "'mask': 'AAA-9999'", 'data-mask']) }}
                            @if ($errors->has('plate_number'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('plate_number') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('reg_number') ? ' has-danger' : '' }}">
                            <label>Registration Number</label>
                            {{ Form::text('reg_number', null, ['class' => 'form-control', 'id' => 'inputReward', 'placeholder' => 'Enter Registration Number']) }}
                            @if ($errors->has('reg_number'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('reg_number') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            

            <div class="form-row">
                   <div class="col-md-12">
                        <div class="form-group {{ $errors->has('card_list') ? ' has-danger' : '' }}">
                                <label for="selectCard">RFID Sticker </label>
                                @if(!Request::is('trucks/create'))
                                    {!! Form::select('card_list', $cards, count($truck->card) == 0 ? 'null' : $truck->card->CardID, ['placeholder' => 'Select Deploy RFID',  'class' => 'form-control select2-card'] ) !!}
                                    @if(count($truck->drivers) == null)
                                         <div class="form-control-feedback">
                                            <small style="color: red">
                                                <em>
                                                    This truck has no driver assigned
                                                </em>
                                            </small>
                                         </div>
                                    @endif
                                @else
                                    {!! Form::select('card_list', $cards, null, ['placeholder' => 'Select Deploy RFID', 'class' => 'form-control select2-card'] ) !!}
                                @endif
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

                    {{--  <div class="col-md-6">
                    <div class="form-group {{ $errors->has('base_list') ? ' has-danger' : '' }}">
                            <label>Base Truck</label>
                            {!! Form::select('base_list', $bases, null, ['placeholder' => 'Select Capacity', 'class' => 'form-control'] ) !!}
                            @if ($errors->has('base_list'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('base_list') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>  --}}
            </div>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('vendor_description') ? ' has-danger' : '' }}">
                                <label>Vendor Number</label>

                                @if(Request::is('trucks/create'))
                                    {!! Form::select('vendor_description', $haulers, null, ['placeholder' => 'Select Vendor','class' => 'form-control select2-vendor'] ) !!}
                                @else
                                    @if($truck->vendor_description == null)
                                        {!! Form::select('vendor_description', $haulers, '0000002000', ['placeholder' => 'Select Vendor','class' => 'form-control select2-vendor'] ) !!}
                                    @else                                       
                                        {!! Form::select('vendor_description', $haulers, $truck->vendor_description, ['placeholder' => 'Select Vendor','class' => 'form-control select2-vendor'] ) !!}
                                    @endif
                                @endif

                                @if ($errors->has('vendor_description'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('vendor_description') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('hauler_list') ? ' has-danger' : '' }}">
                                <label>Subvendor Number</label>
                                 @if(Request::is('trucks/create'))
                                        {!! Form::select('hauler_list', $haulers_subcon, null, ['placeholder' => 'Select Subvendor', 'class' => 'form-control select2-subvendor'] ) !!}
                                @else                                   
                                    {!! Form::select('hauler_list', $haulers_subcon, $truck->subvendor_description, ['placeholder' => 'Select Subvendor', 'class' => 'form-control select2-subvendor'] ) !!}
                                     @if($truck->subvendor_description == null)
                                    <div class="form-control-feedback">
                                        <small style="color: red">
                                            <em>
                                                NO HAULER ASSIGNED
                                            </em>
                                        </small>
                                    </div>
                                    @endif
                                @endif
                                @if ($errors->has('hauler_list'))
                                    <div class="form-control-feedback">
                                    <small>
                                        {{ $errors->first('hauler_list') }}
                                        </small>
                                    </div>
                                @endif
                        </div>
                    </div>
                </div>

                     <div class="form-row">
                     <div class="col-md-12">
                        <div class="form-group {{ $errors->has('documents') ? ' has-danger' : '' }}">
                            <label for="exampleInputFile">Upload Documents</label>
                            <input type="file" name="documents" class="form-control-file filestyle"  data-size="sm" data-btnClass="btn-primary" data-buttonBefore="true" data-size="lg" id="exampleInputFile">
                            @if ($errors->has('documents'))
                                <div class="form-control-feedback">
                                    <small>
                                    {{ $errors->first('documents') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
        

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('validity_start_date') ? ' has-danger' : '' }}">
                            <label>Start Validity Date</label>
                            {!! Form::input('date', 'validity_start_date', $truck->validity_start_date, ['class' => 'form-control'] ) !!}
                            @if ($errors->has('validity_start_date'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('validity_start_date') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('validity_end_date') ? ' has-danger' : '' }}">
                            <label>End Validity Date</label>
                            {!! Form::input('date', 'validity_end_date', $truck->validity_end_date, ['class' => 'form-control'] ) !!}
                            @if ($errors->has('validity_end_date'))
                                <div class="form-control-feedback">
                                <small>
                                    {{ $errors->first('validity_end_date') }}
                                    </small>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
            
        
        
            <button type="submit"  class="btn btn-primary btn-block">Submit</button>
      

@section('script')
    <script>
        $("[data-mask]").inputmask();
        $(".select2-vendor").select2();
        $(".select2-subvendor").select2();
        $(".select2-capacity").select2();

        $(".select2-contract").select2({
            placeholder: "Select Contract",
            allowClear: true
        });
        $(".select2-card").select2();
        $(".select2-plant").select2();

        $('#sPriceRewards').on('input', function() {    
            if($(this).val().length)
            $('#inputReward').prop('disabled', true);
            else
            $('#inputReward').prop('disabled', false);
        });

    </script>
  
@endsection