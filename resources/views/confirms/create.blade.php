@extends('layouts.app')
@section('content')


     <div class="card mx-auto">
        <div class="card-header">
        Confirm

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-12">
                
                      @if($driver->notif_status != 1)

                        <form method="POST" action="{{ url('/confirm/'.$id.'/'.$plate) }}">
                            {!! csrf_field() !!}
                            @include('confirms.form')
                        </form>

                        @else

                        <p style="font-size: 40px; font-weight: 300;">Ooops. We couldn't find this request :( </p>
                        

                        @endif
                
                </div>
            </div>
        
        
        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection