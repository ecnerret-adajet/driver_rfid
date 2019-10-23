<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DequeueNotification;
use App\Dequeue;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\QueueEntry;
use App\Setting;

class DequeuesApiController extends Controller
{

    public function index()
    {
        $dequeues =  Dequeue::orderBy('id','desc')->get();
        return $dequeues;
    }

    public function create(QueueEntry $queueEntry)
    {   
        return $queueEntry;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'remarks' => 'required',
            'queue_entry_id' => 'required'
        ]);

        $dequeue = new Dequeue();
        $dequeue->user_id = Auth::user()->id;
        $dequeue->queue_entry_id = $request->input('queue_entry_id');
        $dequeue->remarks = $request->input('remarks');
        $dequeue->save();

        // $queue = QueueEntry::findOrFail($request->input('queue_entry_id'));

        //send email to supervisor for approval
        $setting = Setting::first();
        // Notification::send(User::where('id', $setting->user->id)->get(), new DequeueNotification($queue));

        return $dequeue;

    }

    public function confirmDequeue(Request $request, Dequeue $dequeue)
    {
        $this->validate($request, [
            'isApproved' => 'required'
        ]);

        $dequeue->confirmed_by = Auth::user()->id;
        $dequeue->isApproved = $request->input('isApproved');
        $dequeue->save();

        return $dequeue;
    }
}
