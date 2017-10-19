   @extends('layouts.feeds')   
      @section('feed-section') 

    <div class="row pb-5">
        <div class="col-sm-12">
                  <ul class="list-group">
            @foreach($barriers as $index => $result)
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

                        <img class="img-responsive rounded" style="height: 500px; width: auto; margin-left: 50px;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
                    
                    </div>
                    <div class="col-sm-6">
           
                <div class="row"> 
                <table class="table table-bordered mb-0">
                    <tr>
                        <td colspan="2">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            <span style="font-size: 35px;">
                            {{$driver->name}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small class="text-muted">PLATE NUMBER:</small><br/>
                             @foreach($driver->trucks as $truck)
                            <span style="font-size: 35px;">
                                {{$truck->plate_number}}
                            </span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            @foreach($driver->haulers as $hauler)
                            <span style="font-size: 35px;">
                                {{$hauler->name}}
                             </span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <small class="text-muted">PLANT IN:</small><br/>
                        <?php $barrier_final_in = ''; ?>
                        @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                         <span style="font-size: 35px;">
                            {{ $barrier_final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} 
                        </span>
                        @empty
                          <span style="font-size: 35px;">
                            NO IN  
                        </span>
                        @endforelse
                        </td>
                    </tr>
                    <tr>
                     <td>
                      <small class="text-muted">PLANT OUT:</small><br/>
                         <?php $barrier_final_out = ''; ?>                                     
                        @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                         <span style="font-size: 35px;">
                        {{ $barrier_final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} 
                        </span>
                        @empty
                         <span style="font-size: 35px;">
                        NO OUT
                        </span>
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
