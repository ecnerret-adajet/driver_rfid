<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Driver;
use App\Truck;
use App\Hauler;

class ConfirmDriver extends Notification
{
    use Queueable;

    protected $driver;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        if(count($this->driver->clasification) == 0) {
            $status = 'added';
        } else {
            $status = 'updated';
        }

       $truck = Truck::select('plate_number')->with(['drivers' => function ($query) {
            $query->where('id', $this->driver->id);
        }])->first();

        $hauler = Hauler::select('name')->with(['drivers' => function ($query) {
            $query->where('id', $this->driver->id);
        }])->first();

        return (new MailMessage)
            ->success()
            ->subject('Trucking Monitoring: Driver RFID Confirmation')
            ->greeting('Good day!')
            ->line($this->driver->user->name. ' has '. $status .' a new driver for your review, please see the details below.')
            ->line($this->driver->name. ' - '. $truck . ' - '. $hauler)
            ->action('Confirm Now', url('/confirm/'.$this->driver->id))
            ->line('Thank you for using our application!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
