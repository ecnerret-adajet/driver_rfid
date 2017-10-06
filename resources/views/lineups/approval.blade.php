@extends('layouts.app')
@section('content')


     <div class="card mx-auto">
        <div class="card-header">
        Confirm Hustling

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-12">
                
                      @if($lineup->notif_status != 1)

                        <form method="POST" action="{{ url('/lineups/approval/'.$lineup->id) }}">
                            {!! csrf_field() !!}

                            
<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('status') ? ' has-danger' : '' }}">
                <label>Status</label>
                {!! Form::select('status', ['Approve' => 'Approve', 'Disapprove' => 'Disapprove'], null, ['placeholder' => 'Select Status','class' => 'form-control'] ) !!}
                
                @if ($errors->has('status'))
                    <div class="form-control-feedback">
                    <small>
                        {{ $errors->first('status') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>



<div class="form-row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('remarks') ? ' has-danger' : '' }}">
                <label>Remarks</label>
                {!! Form::textarea('remarks', null, ['placeholder' => 'Enter Remarks','class' => 'form-control','rows' => '3'] ) !!}
                @if ($errors->has('remarks'))
                    <div class="form-control-feedback">
                    <small>
                        {{ $errors->first('remarks') }}
                        </small>
                    </div>
                @endif
        </div>
    </div>
</div>

<button type="submit"  class="btn btn-primary btn-block">Submit</button>


                            
                        </form>

                        @else

                        <p style="font-size: 40px; font-weight: 300;">Ooops. We couldn't find this request :( </p>
                        
                        @endif
                
                </div>
            </div>
        
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection