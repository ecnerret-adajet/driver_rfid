<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class SmsController extends Controller
{

    public function index()
    {
        return view('sms.index');
    }

    public function receiveReassign()
    {

        @$num = $_GET['num'];
        @$port = $_GET['port'];
        @$message = $_GET['message'];
        @$time = $_GET['time'];

        if ($message = 'YES') {
            $x = urlencode(mb_convert_encoding($message,'utf-8','gb2312'));
            $response = Curl::to('http://10.96.2.20/sendsms?username=truckingsms&password=P@ssw0rd123&phonenumber=09175699879&message='.$x)->get();
            return $response;
        } else {
            $x = urlencode(mb_convert_encoding($message,'utf-8','gb2312'));
            $response = Curl::to('http://10.96.2.20/sendsms?username=truckingsms&password=P@ssw0rd123&phonenumber=09175699879&message='.$x)->get();
            return $response;
        }
       
    
        // if($this->getApprovalKey($message) == 'APPROVE') {
        //   return  $this->approveReassign($this->getReferenceNumber($message));
        // } 

        // if($this->getApprovalKey($message) == 'REJECT') {
        //   return  $this->rejectReassign($this->getReferenceNumber($message));
        // }

    }

    //  Approved Message sent back to approver
    public function sendFeedbackApproved($message)
    {
        $x = urlencode(mb_convert_encoding($message,'utf-8','gb2312'));
        $response = Curl::to('http://10.96.2.20/sendsms?username=truckingsms&password=P@ssw0rd123&phonenumber=09175699879&message='.$x)->get();
        return $response;
    }

    //   Reject Message sent back to approver
    public function sendFeedbackDisapproved()
    {
        $message = urlencode(mb_convert_encoding('You DISAPPROVED successfully!','utf-8','gb2312'));
        $response = Curl::to('http://10.96.2.20/sendsms?username=truckingsms&password=P@ssw0rd123&phonenumber=09175699879&message='.$message)->get();
        return $response;
    }

    /**
     * 
     *  Get the reference number texted by approver
     * 
     */
    public function getReferenceNumber($message)
    {
        $remove_spaces = strtoupper(str_replace(' ','',$message));
        $getRef = preg_replace('/\D/','',$remove_spaces);
        return $getRef;
    }

    /**
     * 
     *  Get wether a test is "APPROVED" or "REJECT"
     * 
     */
    public function getApprovalKey($message)
    {
        $remove_spaces = strtoupper(str_replace(' ','',$message));
        $getApproval = preg_replace('/[0-9]+/','',$remove_spaces);
        return $getApproval;
    }

    /**
     * 
     *  When the approver accept and confirm the driver's reassign
     * 
     */
    public function approveReassign($ref_number)
    {
        // $setting = Setting::first();
        // $driver = Driver::where('id',$ref_number)->first();

        // // Confirm Model
        // $confirm = new Confirm;
        // $confirm->driver_id = $driver-id;
        // $confirm->user_id = $setting->user->id;
        // $confirm->classification = 'Reassign Driver';
        // $confirm->remarks = 'From SMS';
        // $confirm->status = 'Approve';
        // $confirm->save();

        // //Drivers Record
        // $driver->notif_status = 1;
        // $driver->availability = 1;
        // // Activating Card form ASManager
        // if(!empty($driver->card_id)) {
        //     $card = Card::where('CardID',$driver->card_id)->first();
        //     $card->CardStatus = 0; 
        // }
        // $driver->save();

        $this->sendFeedbackApproved();
    }

    /**
     * 
     *  When the approver recjet a driver's reassign
     * 
     */
    public function rejectReassign($ref_number)
    {
        // $setting = Setting::first();
        // $driver = Driver::where('id',$ref_number)->first();

        // // Confirm Model
        // $confirm = new Confirm;
        // $confirm->driver_id = $driver-id;
        // $confirm->user_id = $setting->user->id;
        // $confirm->classification = 'Reassign Driver';
        // $confirm->remarks = 'From SMS';
        // $confirm->status = 'Approve';
        // $confirm->save();

        $this->sendFeedbackDisapproved();
    }
}
