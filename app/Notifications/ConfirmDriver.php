<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Driver;
use App\Truck;

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

        foreach($this->driver->trucks as $truck){
            $truck_name = $truck->plate_number;

            foreach($truck->haulers as $hauler){

            $hauler_name = $hauler->name;

            }
        }

        

        return (new MailMessage)
            ->success()
            ->subject('Trucking Monitoring: Driver RFID Confirmation')
            ->greeting('Good day!')
            ->line($this->driver->user->name. ' has '. $status .' a driver for your review, please see the details below.')
            ->line($this->driver->name. ' - '. $hauler_name . ' - '. $truck_name)
            ->action('Confirm Now', url('/confirm/create/'.$this->driver->id))
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
