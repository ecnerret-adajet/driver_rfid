@extends('layouts.app')
@section('content')

            <div class="row mb-3 mb-2">

                <div class="col-sm-12">

                 <div class="card mx-auto">
                    <div class="card-header">
                        Add New Plant Location

                        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
                        Back
                        </a>
                    </div>
                    <div class="card-body" id="form-truck">
                      

                        {!! Form::model($area = new \App\Area, ['url' => 'areas', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                        {!! csrf_field() !!}
                      
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
                                             <label>Location Name</label>
                                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                                            @if ($errors->has('name'))
                                                <div class="form-control-feedback">
                                                    <small>
                                                    {{ $errors->first('name') }}
                                                    </small>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <button type="submit"  class="btn btn-primary btn-block p-2">PUBLISH</button>
                
                        {!! Form::close() !!}
                </div>
                </div>
                </div>
            </div><!-- end row -->

            
            <div class="row mb-3">

                <div class="col-sm-12">

                 <div class="card mx-auto">
                    <div class="card-header">
                        Plant Locations
                    </div>
                    <div class="card-body">

                    <ul class="list-group list-group-flush">
                        @foreach($areas as $area)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $area->name }}
                            <span class="badge badge-primary badge-pill">0</span>
                        </li>
                        @endforeach
                    </ul>                      
                
                </div>
                </div>



                </div>

                
            </div><!-- end row -->



@endsection