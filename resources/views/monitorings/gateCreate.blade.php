@extends('layouts.app')
@section('content')

            <div class="row mb-3">

                <div class="col-sm-12">

                 <div class="card mx-auto">
                    <div class="card-header">
                        Add New Gate Monitoring

                        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                        Back
                        </a>
                    </div>
                    <div class="card-body" id="form-truck">
                      

                        {!! Form::model($gate = new \App\Gate, ['url' => 'gates/store', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                        {!! csrf_field() !!}
                        <form>


                      
                      
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('title') ? ' has-danger' : '' }}">
                                             <label>Title</label>
                                            {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                            @if ($errors->has('title'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('title') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('area_list') ? ' has-danger' : '' }}">
                                             <label>Queue Area</label>
                                            {{ Form::select('area_list', $areas, null, ['placeholder' => 'Select Plant Location','class' => 'p-2 form-control select2-area']) }}
                                            @if ($errors->has('area_list'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('area_list') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('door') ? ' has-danger' : '' }}">
                                        <label>Door ID</label>
                                        {{ Form::number('door', null, ['class' => 'form-control', 'placeholder' => 'Enter Door ID']) }}
                                        @if ($errors->has('door'))
                                            <div class="form-control-feedback">
                                                <small>
                                                {{ $errors->first('door') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('controller') ? ' has-danger' : '' }}">
                                        <label>Controller ID</label>
                                        {{ Form::number('controller', null, ['class' => 'form-control', 'placeholder' => 'Enter Controller ID']) }}
                                        @if ($errors->has('controller'))
                                            <div class="form-control-feedback">
                                                <small>
                                                {{ $errors->first('controller') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <button type="submit"  class="btn btn-primary btn-block p-2">PUBLISH</button>

                              </form>
                        {!! Form::close() !!}

                  
                </div>
                </div>



                </div>

                
            </div><!-- end row -->


@endsection
@section('script')
    <script>
        $(".select2-area").select2({
            placeholder: "Select Plant Location",
            allowClear: true,
        });
    </script>
@endsection