   @extends('layouts.feeds')   
      @section('feed-section') 

       <!-- Icon Cards -->
        {{--  <div class="row">
    
          <div class="col-xl-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                </div>
                <div class="mr-5">
                  TRUCKS IN PLANT
                </div>
               <div v-if="!is_loading">
                    <h3>
                    {{ $barrier_in->count() - $barrier_out->count() }}
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
                  TRUCKS IN TRANSIT
                </div>
                <div v-if="!is_loading">
                    <h3>
                        {{ $barrier_out->count() }}
                    </h3>
                </div>
              
              </div>
              <a href="#" class="card-footer text-white clearfix small z-1">
                <span class="float-right">
                </span>
              </a>
            </div>
          </div>
        </div>  --}}



    <div class="row pb-5">
        <div class="col-sm-12">
                  <ul class="list-group">
            @foreach($barrier_results as $index => $result)
             @foreach($result->drivers as $driver)
                @if(count($result->drivers) != 0)


 <li class="list-group-item pb-0 pt-0" style="border-color:{{ $driver->availability == 1 ? '#28a745' : '#d58393' }} ">
                <div class="row">
                    <div class="col-sm-6 p-2" style="border-right: 1px solid {{ $driver->availability == 1 ? '#28a745' : '#d58393' }}">

                         @if($driver->availability == 1)
                            <a class="btn btn-sm btn-success pull-right btn-outline disabled" href="#">
                                ACTIVE
                            </a>
                        @else
                             <a class="btn btn-sm btn-danger pull-right btn-outline disabled" href="#">
                                INACTIVE
                            </a>
                        @endif

                        <img class="img-responsive" style="height: 300px; width: auto; padding-left: 150px;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
                    
                    </div>
                    <div class="col-sm-6">
           
                <div class="row text-center"> 
                <table class="table table-bordered mb-0">
                    <tr>
                        <td colspan="2">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            {{$driver->name}}
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <small class="text-muted">PLATE NUMBER:</small><br/>
                             @foreach($driver->trucks as $truck)
                                {{$truck->plate_number}}
                            @endforeach
                        </td>
                         <td width="50%">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            @foreach($driver->haulers as $hauler)
                                {{$hauler->name}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                        <small class="text-muted">PLANT IN:</small><br/>
                        <?php $barrier_final_in = ''; ?>
                        @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                            {{ $barrier_final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} 
                        @empty
                            NO IN  
                        @endforelse
                        </td>
                     <td width="50%">
                      <small class="text-muted">PLANT OUT:</small><br/>
                         <?php $barrier_final_out = ''; ?>                                     
                        @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                        {{ $barrier_final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} 
                        @empty
                        NO OUT
                        @endforelse
                        </td>
                    </tr>
                </table>
                </div>
           
                    </div>
                </div><!--end row -->

                 </li>


     



            @endif
         @endforeach
         @endforeach
            
            </ul>
        </div>
      </div>


            
         

    @endsection
