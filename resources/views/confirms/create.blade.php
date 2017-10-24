@extends('layouts.app')
@section('content')


     <div class="card mx-auto mb-3">
        <div class="card-header">
        Review Application

     
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-12">
                
                    @if ($driver->notif_status == 1)

                        @if($driver->notif_status == 1 && $driver->availability == 0 && $driver->print_status == 1)
                            @include('confirms.new')         
                        @endif 

                         @if($driver->notif_status == 1 && $driver->availability == 0 && $driver->print_status == 0)
                            @include('confirms.reassign') 
                        @endif           

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