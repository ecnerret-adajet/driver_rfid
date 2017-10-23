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
                                    </tr>
                                    <tr>
                                        <td width="50%">
                                                <small class="text-muted">HAULER NAME:</small><br/>
                                            @foreach($driver->haulers as $hauler)
                                            {{ $hauler->name }}
                                            @endforeach
                                        </td>

                                        <td>
                                            <small class="text-muted">PLATE NUMBER:</small><br/>
                                            @foreach($driver->trucks as $truck)
                                            {{ $truck->plate_number }}
                                            @endforeach
                                        </td>
                                    </tr>
                                     <tr>
                                        <td width="50%">
                                            <small class="text-muted">NBI NUMBER:</small><br/>
                                            {{ $driver->nbi_number }}
                                        </td>
                                        
                                        <td>
                                            <small class="text-muted">LICENSE NUMBER:</small><br/>
                                             {{ $driver->driver_license }}
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