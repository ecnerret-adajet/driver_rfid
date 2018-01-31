@extends('layouts.app')
@section('content')

  <!-- Breadcrumbs -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">My Dashboard</li>
    </ol>


    <home></home>


  <div class="card mx-auto mb-0">
            <div class="card-header">

              <div class="col-sm-12">
                                {{ Form::open(array('url' => '/generatePickups', 'method' => 'get')) }}
                                    <form>

                                    <div class="form-row">
                                        <div class="col-md-10">
                                            <div class="form-group {{ $errors->has('start_date') ? ' has-danger' : '' }}">
                                                    <label>Search by Date</label>
                                                    {!! Form::input('date','start_date', Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'max' => ''.date('Y-m-d', strtotime(Carbon\Carbon::now())).'' ]) !!}
                                                    @if ($errors->has('start_date'))
                                                        <div class="form-control-feedback">
                                                        <small>
                                                            {{ $errors->first('start_date') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit"  class="btn btn-outline-primary btn-block">GENERATE</button>
                                        </div>
                                    </div>

                                    
                                    </form>
                                {!! Form::close() !!} 
                            </div>   
         
        </div>
    </div>
     <dashboard></dashboard>




@endsection

