   @extends('layouts.feeds')   
      @section('feed-section') 
      @inject('feed', 'App\Http\Controllers\FeedsController')

      <div class="row mb-3">
        <div class="col-sm-4 text-center text-muted">
            DRIVER'S INFORMATION
        </div>
        <div class="col-sm-3 text-center text-muted">
            TRUCSCALE IN/OUT
        </div>
        <div class="col-sm-3 text-center text-muted">
           PLANT IN/OUT
        </div>
        <div class="col-sm-2 text-center text-muted">
           CAMERA
        </div>
      </div>
    
      <div class="row pb-5">
        <div class="col-sm-12">
                  <ul class="list-group">
            @foreach($today_result as $index => $result)
                 @if(count($result->drivers) != 0)

            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-1">
                        @foreach($result->drivers as $driver)
                            @if(!empty($driver->image))
                                <img class="img-responsive rounded-circle"  style="height: 60px; width: auto;" src="{{asset('/storage/'. $driver->image->avatar)}}" align="middle">
                            @else
                                <img class="img-responsive rounded-circle"  style="height: 60px; width: auto;" src="{{asset('/storage/'. $driver->avatar)}}" align="middle">
                            @endif
                        @endforeach
                    </div>
                    <div class="col-sm-3 pt-0 mt-0">
                         @foreach($result->drivers as $driver)
                        <span style="text-transform: uppercase">{{$driver->name}} </span>
                        @endforeach
                        <br/>
                        @foreach($result->drivers as $driver)
                            @foreach($driver->trucks as $truck)
                             <small class="text-muted"> {{$truck->plate_number}} </small>
                            @endforeach
                        @endforeach
                        <br/>
                        @foreach($result->drivers as $driver)
                            @foreach($driver->haulers as $hauler)
                                 <small class="text-muted">  {{$hauler->name}} </small>
                            @endforeach
                        @endforeach 
                        <br/>

                        <!-- show all customer-->
                        @foreach($result->customers as $customer)
                            {{  str_limit(title_case($customer->name),35) }}<br/>
                        @endforeach

                       {{--  <a class="btn btn-outline-primary btn-sm" href="{{ url('/picklist/'.$driver->id.'/'.$result->LogID) }}" >
                            PICK LIST
                        </a>    --}}


                    </div>
                    <div class="col-sm-3">

                    
                    <small class="text-muted">PLANT IN:</small></br>
                    <?php $barrier_final_in = ''; ?>
                    @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                        <span> {{ $barrier_final_in = date('m/d/y h:i:s A', strtotime($in->LocalTime))}} </span>
                    @empty
                        <span>  NO IN </span>
                    @endforelse
                    <br/>
                    <small class="text-muted">PLANT OUT:</small></br>
                    <?php $final_barrier_out = ''; ?>                                     
                    @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                    <span> {{ $final_barrier_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} </span>
                    @empty
                    <span>NO OUT</span>
                    @endforelse
                    <br/>
                     <small class="text-muted">TIME BETWEEN:</small></br>
                     @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out )
                    @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in )
                    <span> {{  $in->LocalTime->diffInHours($out->LocalTime)}} Hour(s) </span> 
                    @empty
                    <span>  NO PLANT TIME IN </span>                        
                    @endforelse
                    @empty
                    <span>NO PLANT TIME OUT </span> 
                    @endforelse

                    

                    </div>
                    <div class="col-sm-3 right">
                        <small class="text-muted">TRUCKSCALE IN:</small></br>
                        <?php $final_in = ''; ?>
                        @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                            <span> {{ $final_in = date('m/d/y h:i:s A', strtotime($in->LocalTime))}} </span>
                        @empty
                            @forelse($all_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                                <span> {{ $final_in = date('m/d/y h:i:s A', strtotime($in->LocalTime))}} </span>
                            @empty
                            <span> NO IN </span>
                            @endforelse  
                        @endforelse
                        <br/>
                         <small class="text-muted">TRUCKSCALE OUT:</small></br>
                          <?php $final_out = ''; ?>                                     
                        @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                        <span> {{ $final_out = date('m/d/y h:i:s A', strtotime($out->LocalTime))}} </span>
                        @empty
                        <span>NO OUT</span>
                        @endforelse
                        <br/>
                         <small class="text-muted">TIME BETWEEN:</small></br>

                        @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out )
                        @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in )
                        <span> {{  $in->LocalTime->diffInHours($out->LocalTime)}} Hour(s) </span> 
                        @empty
                            @forelse($all_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in2)
                                <span> {{  $in2->LocalTime->diffInHours($out->LocalTime)}} Hour(s) </span>
                            @empty
                            <span>  NO PAIRED TIME IN </span>
                            @endforelse                         
                        @endforelse
                        @empty
                        <span>NO PAIRED TIME OUT </span> 
                        @endforelse

                    </div>
            
                    <div class="col-sm-2">
                        <small class="text-muted">CAMERA IN:</small></br>
                         @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)

                        <!-- test code for checking image if exist -->

                                <a class="btn btn-sm btn-outline-success mb-2" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($in->LocalTime)).'/AC.'.date('Ymd',strtotime($in->LocalTime)).'.0000'.$in->LogID.'-1.jpg')}}" data-lightbox="{{$result->LogID}}" data-title="TIME IN - {{  date('Y-m-d h:i:s A', strtotime($in->LocalTime))}}">                      
                               <i class="fa fa-camera" aria-hidden="true"></i>
                                </a>
                         

                        @empty
                            @forelse($all_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in2)
                                <a class="btn btn-sm btn-outline-success mb-2" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($in2->LocalTime)).'/AC.'.date('Ymd',strtotime($in2->LocalTime)).'.0000'.$in2->LogID.'-1.jpg')}}" data-lightbox="{{$result->LogID}}" data-title="TIME IN - {{  date('Y-m-d h:i:s A', strtotime($in2->LocalTime))}}">                      
                                     <i class="fa fa-camera" aria-hidden="true"></i>
                                </a>
                            @empty
                            <span>  NO IN </span>
                            @endforelse  
                        @endforelse

                        <br/>
                         <small class="text-muted">CAMERA OUT:</small></br>   
                         <?php $final_out = ''; ?>                                     
                        @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                            <a class="btn btn-sm btn-outline-primary" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($out->LocalTime)).'/AC.'.date('Ymd',strtotime($out->LocalTime)).'.0000'.$out->LogID.'-2.jpg')}}" data-lightbox="{{$result->LogID}}" data-title="TIME OUT - {{  date('Y-m-d h:i:s A', strtotime($out->LocalTime))}}">                      
                                  <i class="fa fa-camera" aria-hidden="true"></i>
                            </a>
                        @empty
                        <span>NO OUT</span>
                        @endforelse

                        <br/>

                        @foreach($result->drivers as $driver)
                        <?php
                        $card = App\Log::match($result->LogID)->pluck('CardholderID','CardholderID');
                        ?>
                        @if(array_has($card,$result->CardholderID))
                            <span class="btn btn-sm btn-outline-success btn-xs mt-2 disabled">
                                MATCHED
                            </span>
                        @else
                            <span class="btn btn-sm btn-outline-danger btn-xs mt-2 disabled">
                                NO FOUND
                            </span>
                        @endif
                        @endforeach

            

                    
                    </div>
                
                </div>

              
                
                
            </li>
             @endif
            @endforeach
            </ul>
        </div>
      </div>

       
            
           

    @endsection
   @section('script')
   <script>

      var currURL = "";
            function showMapModal(customer_address){
                var url = "http://www.google.com/maps/embed/v1/directions?origin=L2-3+B1+BV+Romero+Blvd,+Tondo,+Manila,+Tondo,+Manila,+Metro+Manila&destination="+ customer_address +"&key=AIzaSyDmCmQ3m-UNz1j1reAgrTcGNu1zLcm7FJc";
                if(currURL != url) //avoid reloading same map
                {
                    $('#frame_map').attr('src', url)
                }
                $('#myModal').modal('show'); 
                currURL = url;
            }   

   </script>
   @endsection
   @extends('map_modal')