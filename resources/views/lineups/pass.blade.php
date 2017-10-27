@inject('search', 'App\Http\Controllers\LineupsController')
<!-- put drivers , logs , and outstanding DR if any -->
<div class="row">
    <div class="col-sm-12">
        <ul class="list-group">

        @foreach($barrier_unique as $index => $result)
             @foreach($result->drivers as $driver)
                @if(count($result->drivers) != 0)

                <li class="list-group-item justify-content-between">
                    <div class="row">
                        <div class="col-sm-1">
                            <img class="rounded-circle" style="height: 60px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
                        </div>                
                        <div class="col-sm-5">
                            <span style="text-transfer: uppercase"> {{ $driver->name }} </span>
                            <br/>
                            @foreach($driver->trucks as $truck)
                            {{ $truck->plate_number }}
                            @endforeach
                            <br/>
                            @foreach($driver->haulers as $hauler)
                            {{ $hauler->name }}
                            @endforeach
                        </div> 
                        <div class="col-sm-6 pull-right right">
                                <small class="text-muted">PLANT IN:</small><br/>
                                <?php $barrier_final_in = ''; ?>
                                @forelse($barrier_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                                <span>
                                    {{ $barrier_final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} 
                                </span>
                                @empty
                                <span>
                                    NO IN  
                                </span>
                                @endforelse 
                                <br/>
                                   <small class="text-muted">PLANT OUT:</small><br/>
                                    <?php $barrier_final_out = ''; ?>                                     
                                    @forelse($barrier_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                                    <span>
                                    {{ $barrier_final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} 
                                    </span>
                                    @empty
                                    <span>
                                    NO OUT
                                    </span>
                                    @endforelse

                        </div> 

                      
                    
                    </div><!-- end row -->
                    <span class="badge badge-default badge-pill">14</span>
                </li>

                @endif
             @endforeach
         @endforeach


        </ul>        
    </div>
</div>



 @foreach($barrier_unique as $index => $result)
             @foreach($result->drivers as $driver)
                @if(count($result->drivers) != 0)

     <!-- Logout Modal -->
        <div class="modal fade" id="driverPass-{{$result->LogID}}" tabindex="-1" role="dialog" aria-labelledby="lineupModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="lineupter">
            <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="lineupModalLabel">Driver Pass</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            
    
            </div>
            <div class="modal-body">

                                      
               <em>Are you sure you want to proceed with this action?</em>
            

            </div>
            <div class="modal-footer">                
            <form id="lineup-form" method="POST" action="{{url('/passes/'.$driver->id.'/'.$result->LogID)}}">
                {{ csrf_field() }}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button> 
            </form>              
            </div>
                
            </div>
        </div>
        </div>

            @endif
        @endforeach
    @endforeach



