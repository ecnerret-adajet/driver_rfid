   @extends('layouts.feeds')   

      @section('feed-section') 

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
                    {{ $barrier_results->count() }}
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
                        {{ $barrier_in->count() }}
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
            @foreach($barrier_results as $index => $result)
            <li class="list-group-item">

                <div class="row">
                    <div class="col-sm-1">
                        @foreach($result->drivers as $driver)
                            <img class="rounded-circle" style="height: 60px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
                        @endforeach
                    </div>
                    <div class="col-sm-5">
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
                    <div class="col-sm-6 right">
                        <?php $barrier_final_in = ''; ?>
                        @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                            <span> IN: {{ $barrier_final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} </span>
                        @empty
                          <span>  NO IN </span> 
                        @endforelse
                        <br/>
                          <?php $barrier_final_out = ''; ?>                                     
                        @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                        <span> OUT: {{ $barrier_final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} </span>
                        @empty
                        <span>NO OUT</span>
                        @endforelse
                        <br/>

                        @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out )
                        @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in )
                        <span> {{  $in->LocalTime->diffInHours($out->LocalTime)}} Hour(s) </span> 
                        @empty
                            @forelse($barrier_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in2)
                                <span> {{  $in2->LocalTime->diffInHours($out->LocalTime)}} Hour(s) </span>
                            @empty
                            <span>  NO PAIRED TIME IN </span>
                            @endforelse                         
                        @endforelse
                        @empty
                        <span>NO PAIRED TIME OUT </span> 
                        @endforelse

                    </div>
            
                </div>

              
                
                
            </li>
            @endforeach
            </ul>
        </div>
      </div>


            
         

    @endsection
