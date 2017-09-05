   @extends('layouts.feeds')   

      @section('feed-section')            
            
            <div class="col s6">
              <h4 class="black-text">
                 {{$today_result->count()}} <small>TOTAL ENTRIES</small>
              </h4>
            </div>  
            <div class="col s6">
                  
            </div>
         

           

            <div class="col s12">
                <div class="card" style="padding: 0">
                    <div class="card-content"  style="padding: 0">
                    {{--  <span class="card-title">Card Title</span>  --}}

                   


    <div>
        <ul class="collection">
        @foreach($today_result as $index => $result)
            <li  class="collection-item avatar">
            @foreach($result->drivers as $driver)
                <img class="circle" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
            @endforeach
             @foreach($result->drivers as $driver)
                <span class="title" style="text-transform: uppercase">{{$driver->name}} </span>
            @endforeach
            <p>
                @foreach($result->drivers as $driver)
                    @foreach($driver->trucks as $truck)
                        {{$truck->plate_number}}
                    @endforeach
                @endforeach
            </p>
            <p>
             @foreach($result->drivers as $driver)
                @foreach($driver->haulers as $hauler)
                    {{$hauler->name}}
                @endforeach
            @endforeach   
            </p>

            <p class="secondary-content right-align">

                 <?php $final_in = ''; ?>
                @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                    <span class="chip green white-text"> IN: {{ $final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} </span>
                @empty
                    @forelse($all_in_2->where('CardholderID', '==', $result->CardholderID)->take(1) as $in)
                        <span class="chip green white-text"> IN: {{ $final_in = date('Y-m-d h:i:s A', strtotime($in->LocalTime))}} </span>
                    @empty
                       <span class="chip green white-text">  NO IN </span>
                    @endforelse  
                @endforelse


                <?php $final_out = ''; ?>                                     
                @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out)
                <span class="chip blue white-text"> OUT: {{ $final_out = date('Y-m-d h:i:s A', strtotime($out->LocalTime))}} </span>
                @empty
                  <span class="chip blue white-text">NO OUT</span>
                @endforelse    



                @forelse($all_out->where('CardholderID', '==', $result->CardholderID)->take(1) as $out )
                @forelse($all_in->where('CardholderID', '==', $result->CardholderID)->take(1) as $in )
                   <span class="chip">{{  $in->LocalTime->diffInHours($out->LocalTime)}} Hour(s) </span> 
                @empty
                   <span class="chip">  NO PAIRED TIME IN </span>
                @endforelse
                @empty
                   <span class="chip">NO PAIRED TIME OUT </span> 
                @endforelse

            </p> 


    
            </li>
            @endforeach
        </ul>
    </div>


    {{--  <div class="center-align" style="padding-top: 50px">
        <div class="preloader-wrapper small active">
            <div class="spinner-layer spinner-green-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>  --}}


                    </div>
                </div>
            </div>

    @endsection