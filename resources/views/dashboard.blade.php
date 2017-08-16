@extends('layouts.app')

@section('main')

<ul class="collapsible popout" data-collapsible="accordion">
    <li>
        <div class="collapsible-header active"><i class="material-icons">filter_drama</i>Overview</div>
        <div class="collapsible-body grey lighten-3" style="padding: 0;">

        <div class="row" style="margin-bottom: 0;">
            <div class="col s3" style="border-right: 2px solid #ffffff; border-bottom: 2px solid #ffffff;">
                <p style="text-transform: uppercase; color: gray;">All Trucks</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
            <div class="col s3" style="border-right: 2px solid #ffffff; border-bottom: 2px solid #ffffff;">
                <p style="text-transform: uppercase; color: gray;">All Drivers</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
            <div class="col s3" style="border-right: 2px solid #ffffff; border-bottom: 2px solid #ffffff;">
                <p style="text-transform: uppercase; color: gray;">All Haulers</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
            <div class="col s3" style="border-bottom: 2px solid #ffffff;">
                <p style="text-transform: uppercase; color: gray;">All Cards</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
        </div>

        <div class="row" style="margin-bottom: 0;">
            <div class="col s3" style="border-right: 2px solid #ffffff; ">
                <p style="text-transform: uppercase; color: gray;">Total Truck</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
            <div class="col s3" style="border-right: 2px solid #ffffff; ">
                <p style="text-transform: uppercase; color: gray;">Total Truck</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
            <div class="col s3" style="border-right: 2px solid #ffffff; ">
                <p style="text-transform: uppercase; color: gray;">Total Truck</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
            <div class="col s3" >
                <p style="text-transform: uppercase; color: gray;">Total Truck</p>
                <p style="font-size: 25px;">
                30 
                </p>
            </div>
        </div>


        </div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
    </li>
    <li>
        <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
        <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
    </li>
</ul>
@endsection