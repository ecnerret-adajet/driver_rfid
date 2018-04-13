@extends('layouts.online')
@section('top-script')
    <!-- Import sytyle for hover animation -->
     <link href="{{ asset('css/set1.css') }}" rel="stylesheet">
    <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
@section('content')

   <div class="card mx-auto mb-3">
        <div class="card-header">
        Hauler's Managed Driver
        </div>
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-12 mb-3">

                    <div class="grid">
                    	<figure class="effect-ruby">
						<img src="{{ asset('img/truck.jpg') }}" alt="img13"/>
						<figcaption>
							<h2>REASSIGN <span>DRIVER</span></h2>
							<a href="{{ url('/users/hauler/online') }}">View more</a>
						</figcaption>			
					    </figure>
                    </div>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
          

                    <div class="grid">
                        <figure class="effect">
                            <img style="filter: grayscale(100%); -webkit-filter: grayscale(100%);" src="{{ asset('img/driver.jpg') }}" alt="img14"/>
                            <figcaption class="pt-5 mt-5">
                                <h2>ADD NEW <span>DRIVER</span></h2>
                                <span>COMING SOON</span>
                                {{--  <a href="{{ url('/hauler/online/create') }}">View more</a>  --}}
                            </figcaption>			
                        </figure>
                    </div>
                </div>




            </div> <!-- end row -->


            <hr>

            <h1 style="font-weight: 100">User Instruction:</h1>

            <div class="row mt-3">
                <div class="col-sm-6 col-sx-12">
                     
                     <h4 class="mb-3" style="font-weight: 100">
                        Reassigning Drivers
                     </h4>

                     <ul>
                        <li>Click the option button from the right side of the driver's information</li>
                        <li>The button will collapse, then select "Reassign"</li>
                        <li>The user will be redirect to reassign page. where it can select a new plate number and remarks in order to submit the request.</li>
                        <li>Once the form is submitted, the request will be send to approver to confirm the reassignement.</li>
                     </ul>

                </div><!-- end col -->

                 <div class="col-sm-6 col-sx-12">
                       <h4 class="mb-3" style="font-weight: 100">
                        Add New Driver
                     </h4>

                     <ul>
                        <li>Click the "Add new driver" link above</li>
                        <li>Fill all required fields in order to submit the page</li>
                        <li>Once the form is completely submitted, the request will also sends an email notification to approver to confirm the driver creation.</li>
                     </ul>
                 </div>
            </div><!-- end row -->




        </div><!-- end card-body -->
    </div> <!-- end card -->


@endsection


