@extends('layouts.app')
@section('content')

    <div class="row">

        <div class="col s12">
            <h4 class="form-title">Truck Status</h4>
        </div>

        <div class="col s12">
             <div class="card-panel grey lighten-4">
                <div class="row">

                   
                    <div class="col s2">
                     @foreach($log->drivers as $driver)
                        <img alt="" class="circle responsive-img" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}">
                    @endforeach
                    </div>

                    <div class="col s3">

                        <div class="row">
                            <small class="grey-text">DRIVER NAME </small><br/>
                                @foreach($log->drivers as $driver)
                                    {{  $driver->name }}
                                @endforeach   
                        </div>

                        <div class="row">
                              <small class="grey-text">TIME IN </small><br/>
                            <?php $final_in = ''; ?>
                            @forelse($all_in->where('CardholderID', '==', $log->CardholderID)->take(1) as $in)
                                {{ $final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}}
                            @empty
                                <?php $final_in = Carbon\Carbon::now() ?>
                                NO IN   
                            @endforelse  
                        </div>
                    </div>

                     <div class="col s3">
                            <div class="row">
                              <small class="grey-text">PLATE NUMBER </small><br/>
                               @foreach($log->drivers as $driver)
                                    @foreach($driver->trucks as $truck)
                                        {{$truck->plate_number}}
                                    @endforeach
                                @endforeach   
                            </div>

                            <div class="row">
                                 <small class="grey-text">TIME OUT </small><br/>
                                 <?php $final_out = ''; ?>
                                @forelse($all_out->where('CardholderID', '==', $log->CardholderID)->take(1) as $out)
                                    {{ $final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}}
                                    <br/>
                                @empty
                                    NO OUT
                                @endforelse 
                            </div>
                     </div>

                    <div class="col s3">
                          <div class="row">
                              <small class="grey-text">OPERATOR </small><br/>
                              @foreach($log->drivers as $driver)
                                    @foreach($driver->haulers as $hauler)
                                        {{$hauler->name}}
                                    @endforeach
                                @endforeach
                            </div>


                            <div class="row">
                                     <small class="grey-text">TIME BETWEEN </small><br/>
                            @forelse($all_out->where('CardholderID', '==', $log->CardholderID)->take(1) as $out )
                                @forelse($all_in->where('CardholderID', '==', $log->CardholderID)->take(1) as $in )
                                            {{  $in->LocalTime->diffInHours($out->LocalTime)}} Hour(s)
                                        @empty
                                            NO PAIRED TIME IN
                                        @endforelse
                                        @empty
                                            NO PAIRED TIME OUT
                                        @endforelse
                            </div> 
                        
                    </div>
              

                </div>
            </div>
        </div>

        {!! Form::model($monitor = new \App\Monitor, ['url' => '/monitors/'.$id, 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
            {!! csrf_field() !!}
            @include('monitors.form')
        {!! Form::close() !!}

    </div><!-- end row -->

@endsection