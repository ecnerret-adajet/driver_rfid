@extends('layouts.app')
@section('content')


     <div class="card mx-auto mb-3">
        <div class="card-header">
        Review Application

     
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-12">
                
                      @if($driver->notif_status != 1)

                        <div class="row">
                            
                            <div class="col-sm-12">
                                <table class="table table-bordered text-center">
                                <tr>
                                    <td colspan="2">
                                                        <img class="img-responsive" style="height: 150px; width: auto; display:block; margin: 10px auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">

                                    </td>
                                </tr>
                                  <tr>
                                        <td colspan="2">

                                        <small class="text-muted">DRIVER NAME:</small><br/>
                                            {{ $driver->name }}
                                            </td>
                                          
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                                <small class="text-muted">HAULER NAME:</small><br/>
                                            @foreach($driver->haulers as $hauler)
                                            {{ $hauler->name }}
                                            @endforeach
                                        <td>
                                            <small class="text-muted">PLATE NUMBER:</small><br/>
                                            @foreach($driver->trucks as $truck)
                                            {{ $truck->plate_number }}
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                         <small class="text-muted">REGISTERED BY:</small><br/>
                                         {{ $driver->user->name }}
                                        </td>
                                    </tr>
                                  
                                </table>
                            </div>
                        </div>

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