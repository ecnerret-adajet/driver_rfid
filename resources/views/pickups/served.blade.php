
                                  <div class="row">
                            <div class="col-sm-12">
                                <div class="table-response">
                                    <table class="table" width="100%" cellspacing="0" style="font-size:70%">
                                        <thead>
                                            <tr style="text-transform: uppercase">
                                                <th>Pickup #</th>
                                                <th>Driver Details</th>
                                                <th>DO Details</th>
                                                <th>Check Date</th>
                                                <th>Truckscale Date</th>
                                                <th>BETWEEN</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($served as $pick)
                                            <tr class="{{ $pick->availability == 1 ? 'table-danger' : 'table-success' }}">
                                                <td>
                                                    {{ !empty($pick->cardholder->Name) ? $pick->cardholder->Name : 'UNPROCESS' }} <br/>
                                                </td>
                                                <td>
                                                    {{$pick->driver_name}}<br/>
                                                    {{$pick->plate_number}}<br/>
                                                    {{$pick->company}}
                                                </td>

                                                <td>
                                                    {{ $pick->do_number }}
                                                </td>
                                        
                                                <td>
                                                <small class="text-uppercase text-muted">
                                                    Created Date:
                                                </small> 
                                                <br/>
                                                {{ date('m/d/y h:i:s A', strtotime($pick->created_at))}}
                                                <br/>
                                                 <small class="text-uppercase text-muted">
                                                    Checkout Date:
                                                </small> <br/>
                                                @if( date('m/d/y h:i:s A', strtotime($pick->created_at))  != date('m/d/y h:i:s A', strtotime($pick->updated_at)) )
                                                    {{ date('m/d/y h:i:s A', strtotime($pick->updated_at))  }}
                                                @else
                                                    NO TIME OUT
                                                @endif

                                                </td>


                                                <td>

                                                <small class="text-uppercase text-muted">
                                                    Truckscale In:
                                                </small> 
                                                <br/>
                                                    @foreach($search->getTruckscaleIn($pick->cardholder_id, $pick->created_at) as $pick_in )
                                                        {{ $pickin = date('m/d/y h:i:s A',strtotime($pick_in->LocalTime))}}<br/>
                                                    @endforeach
                                                 <small class="text-uppercase text-muted">
                                                    Truckscale Out:
                                                </small> 
                                                <br/>
                                                @foreach($search->getTruckscaleOut($pick->cardholder_id, $pick->created_at) as $pick_in )
                                                        {{ $pickout = date('m/d/y h:i:s A',strtotime($pick_in->LocalTime))}}<br/>
                                                    @endforeach

                                                </td>
            
                                                <td>
                                                    {{--  @forelse(App\Log::pickupOut($pick->cardholder_id, $pick->created_at)->get() as $logOut)

                                                    @forelse(App\Log::pickupIn($pick->cardholder_id, $pick->created_at)->get() as $logIn)

                                                    @if($logOut->LocalTime > $logIn->LocalTime)
                                                    {{  $logIn->LocalTime->diffInHours($logOut->LocalTime)}} Hour(s)
                                                    @else
                                                        N/A
                                                    @endif

                                                    @empty
                                                            N/A
                                                    @endforelse
                                                    @empty
                                                            N/A
                                                    @endforelse  --}}

                                                    @foreach($search->getTruckscaleIn($pick->cardholder_id, $pick->created_at) as $pick_in )
                                                        @foreach($search->getTruckscaleOut($pick->cardholder_id, $pick->created_at) as $pick_out )
                                                        {{  $pick_in->LocalTime->diffInHours($pick_out->LocalTime)}} Hour(s)
                                                        @endforeach
                                                    @endforeach

                                                </td>
                                                <td>    
                                                @if($pick->availability == 1)   
                                                                       
                                                    <a class="btn btn-outline-danger btn-sm" data-toggle="modal" class="dropdown-item"  data-target=".bs-setInactive{{$pick->id}}-modal-lg" href="">
                                                        REMOVE CARD
                                                    </a>

                                                @else

                                                <button type="button" class="btn btn-outline-success btn-sm disabled">SERVED</button>

                                                @endif                              
                                                </td>
                                            </tr>
                                            @endforeach                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>

