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
                                        <td>
                                            <small class="text-muted">HAULER NAME:</small><br/>
                                            @foreach($driver->haulers as $hauler)
                                            {{ $hauler->name }}
                                            @endforeach
                                        <td>
                                    </tr>
                                     <tr>
                                        <td width="50%">
                                            <small class="text-muted">FROM:</small><br/>
                                            {{ $from->plate_number }}
                                        </td>
                                        <td>
                                            <small class="text-muted">TO:</small><br/>
                                             {{ $to->plate_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                         <small class="text-muted">ASSIGNED BY:</small><br/>
                                         {{ $driver->user->name }}
                                        </td>
                                    </tr>
                                  
                                </table>
                            </div>
                        </div>