   @extends('layouts.feeds')   
      @section('feed-section') 

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
                            <strong>
                            {{$driver->name}}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <small class="text-muted">PLATE NUMBER:</small><br/>
                             @foreach($driver->trucks as $truck)
                             <strong>
                                {{$truck->plate_number}}
                            </strong>
                            @endforeach
                        </td>
                         <td width="50%">
                            <small class="text-muted">DRIVER NAME:</small><br/>
                            @foreach($driver->haulers as $hauler)
                            <strong>
                                {{$hauler->name}}
                             </strong>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                        <small class="text-muted">PLANT IN:</small><br/>
                        <?php $barrier_final_in = ''; ?>
                        @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                        <strong>
                            {{ $barrier_final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} 
                        </strong>
                        @empty
                         <strong>
                            NO IN  
                        </strong>
                        @endforelse
                        </td>
                     <td width="50%">
                      <small class="text-muted">PLANT OUT:</small><br/>
                         <?php $barrier_final_out = ''; ?>                                     
                        @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                        <strong>
                        {{ $barrier_final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} 
                        </strong>
                        @empty
                         <strong>
                        NO OUT
                        </strong>
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
