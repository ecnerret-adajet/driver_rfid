  <div class="row">
                            <div class="col-sm-12">
                                <div class="table-response">
                                    <table class="table table-striped" width="100%" id="dataTable" cellspacing="0" style="font-size:70%">
                                        <thead>
                                            <tr style="text-transform: uppercase">
                                                <th>Pickup #</th>
                                                <th>Driver Details</th>
                                                <th>Created Date</th>
                                                <th>DO Details</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($unserved as $pick)
                                            <tr>
                                                <td>
                                                    {{ !empty($pick->cardholder->Name) ? $pick->cardholder->Name : 'UNPROCESS' }} <br/>
                                                </td>
                                                <td>
                                                    {{$pick->driver_name}}<br/>
                                                    {{$pick->plate_number}}<br/>
                                                    {{$pick->company}}
                                                </td>
                                                <td>
                                                    {{ date('m/d/y h:i:s A', strtotime($pick->created_at))}}                                            
                                                </td>
                                                <td>
                                                   {{ $pick->do_number }}
                                                </td>
                                            
                                                <td>    
                                                    <a class="btn btn-outline-danger btn-sm"  data-toggle="modal" data-target=".assign-rfid-{{$pick->id}}" href="javascript:void(0);">
                                                        ASSIGN RFID
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>