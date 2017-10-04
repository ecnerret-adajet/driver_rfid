<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Driver;
use App\Truck;

class ConfirmReassign extends Notification
{
    use Queueable;

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
            $truck_id = $truck->id;
            foreach($truck->haulers as $hauler){
                $hauler_name = $hauler->name;
            }
        }

        return (new MailMessage)
        ->success()
        ->subject('Truck Monitoring: Reassign Confirmation')
        ->greeting('Good day!')
        ->line('A driver: '.$this->driver->name.' has now reassigned to '.$truck_name.' Please confirm by clicking the button below' )
        ->action('Confirm Now', url('/confirm/create/'.$this->driver->id.'/'.$truck_id))
        ->line('This is a generated notification from the system');
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
