<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookingRequest;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use App\Transformers\BookRequestTransformer;
use Carbon\Carbon;

class BookRequestController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bookingRequest.index');
    }

    public function fetchBookRequests()
    {
        $bookRequests = BookingRequest::orderBy('id','desc')->get();

        $manager = new Manager();
        $resource = new Collection($bookRequests, new BookRequestTransformer());

        return $manager->createData($resource)->toArray();
    }

    public function myBookedRequests()
    {
        $bookRequests = BookingRequest::orderBy('id','desc')
                        ->where('user_id',Auth::user()->id)
                        ->get();

        $manager = new Manager();
        $resource = new Collection($bookRequests, new BookRequestTransformer());

        return $manager->createData($resource)->toArray();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_reference' => 'required',
            'order_reference_no' => 'required',
            'booking_date' => 'required',
            'mode_of_shipment' => 'required',
            'ship_type' => 'required',
        ]);

        $bookRequest = new BookingRequest();
        $bookRequest->user_id = Auth::user()->id;
        $bookRequest->order_reference = $request->order_reference;
        $bookRequest->order_reference_no = $request->order_reference_no;
        $bookRequest->booking_date = Carbon::parse($request->booking_date);
        $bookRequest->consignee = $request->consignee;
        $bookRequest->destination = $request->destination;
        $bookRequest->origin = $request->origin;
        $bookRequest->van_no = $request->van_no;
        $bookRequest->ship_type = $request->ship_type;
        $bookRequest->mode_of_shipment = $request->mode_of_shipment;
        $bookRequest->save();

        flashy()->success('Book request has successfully created!');
        return redirect('pickups/online');
        // return $bookRequest;
    }


    public function update(Request $request, BookingRequest $bookingRequest)
    {
        $this->validate($request, [
            'shippers_name' => 'required',
            'plate_number' => 'required',
            'driver_name' => 'required'
        ]);

        $bookingRequest->shippers_name = $request->shippers_name;
        $bookingRequest->plate_number = $request->plate_number;
        $bookingRequest->driver_name = $request->driver_name;
        $bookingRequest->save();

        flashy()->success('Driver has successfully updated!');
        return redirect('booking-requests');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingRequest $bookingRequest)
    {
        return view('bookingRequest.show',compact('bookingRequest'));
    }

}
