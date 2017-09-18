   @extends('layouts.feeds')   

      @section('feed-section') 
      @inject('map', 'App\Http\Controllers\FeedsController') 

       <!-- Icon Cards -->
        <div class="row">
    
          <div class="col-xl-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  CURRENT ENTRIES
                </div>
               <div v-if="!is_loading">
                    <h3>
                    {{ $today_result->count() }}
                    </h3>
                </div>
    
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
 
          <div class="col-xl-6 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  TRUCKS IN PLANT
                </div>
                <div v-if="!is_loading">
                    <h3>
                        {{ $all_in_2->count() }}
                    </h3>
                </div>
              
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
        </div>



    <div class="row pb-5">
        <div class="col-sm-12">
                  <ul class="list-group">
            @foreach($today_result as $index => $result)
            <li class="list-group-item">

                <div class="row">
                    <div class="col-sm-1">
                        @foreach($result->drivers as $driver)
                            <img class="rounded-circle" style="height: 60px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
                        @endforeach
                    </div>
                    <div class="col-sm-4">
                         @foreach($result->drivers as $driver)
                        <span class="title" style="text-transform: uppercase">{{$driver->name}} </span>
                        @endforeach
                        <br/>
                        @foreach($result->drivers as $driver)
                            @foreach($driver->trucks as $truck)
                                {{$truck->plate_number}}
                            @endforeach
                        @endforeach
                        <br/>
                        @foreach($result->drivers as $driver)
                            @foreach($driver->haulers as $hauler)
                                {{$hauler->name}}
                            @endforeach
                        @endforeach 
                        <br/>

                      @foreach($result->customers as $customer)
                            {{  str_limit(title_case($customer->address),35) }}<br/>
                        @endforeach


                    </div>
                    <div class="col-sm-4 right">
                        <?php $final_in = ''; ?>
                        @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                            <span> IN: {{ $final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} </span>
                        @empty
                            @forelse($all_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                                <span> IN: {{ $final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} </span>
                            @empty
                            <span>  NO IN </span>
                            @endforelse  
                        @endforelse
                        <br/>
                          <?php $final_out = ''; ?>                                     
                        @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                        <span> OUT: {{ $final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} </span>
                        @empty
                        <span>NO OUT</span>
                        @endforelse
                        <br/>

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
                    <div class="col-sm-3">

                         @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                            <a class="btn btn-sm btn-success mb-2" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($in->LocalTime)).'/AC.'.date('Ymd',strtotime($in->LocalTime)).'.0000'.$in->LogID.'-1.jpg')}}" data-lightbox="{{$result->LogID}}" data-title="TIME IN - {{  date('Y-m-d h:i:s A', strtotime($in->LocalTime))}}">                      
                               Snapshot - in
                            </a>
                        @empty
                            @forelse($all_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in2)
                                <a class="btn btn-sm btn-success mb-2" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($in2->LocalTime)).'/AC.'.date('Ymd',strtotime($in2->LocalTime)).'.0000'.$in2->LogID.'-1.jpg')}}" data-lightbox="{{$result->LogID}}" data-title="TIME IN - {{  date('Y-m-d h:i:s A', strtotime($in2->LocalTime))}}">                      
                                    Snapshot - in
                                </a>
                            @empty
                            <span>  NO IN </span>
                            @endforelse  
                        @endforelse

                        <br/>

                         <?php $final_out = ''; ?>                                     
                        @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                            <a class="btn btn-sm btn-primary" href="{{url('http://172.17.2.25:8080/RFID/'.date('Ymd',strtotime($out->LocalTime)).'/AC.'.date('Ymd',strtotime($out->LocalTime)).'.0000'.$out->LogID.'-2.jpg')}}" data-lightbox="{{$result->LogID}}" data-title="TIME OUT - {{  date('Y-m-d h:i:s A', strtotime($out->LocalTime))}}">                      
                                Snapshot - out
                            </a>
                        @empty
                        <span>NO OUT</span>
                        @endforelse

                        <br/>


                    
                    </div>
                
                </div>

              
                
                
            </li>
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