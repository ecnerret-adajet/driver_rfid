   @extends('layouts.feeds')   

      @section('feed-section')  



            <ul class="list-group">
            @foreach($today_result as $index => $result)
            <li class="list-group-item">

                <span class="badge">14</span>
                @foreach($result->drivers as $driver)
                    <img class="circle" style="height: 100px; width: auto;" src="{{ str_replace( 'public/','', asset('/storage/app/'.$driver->avatar))}}" align="middle">
                @endforeach
            </li>
            @endforeach
            </ul>

            
         

    @endsection