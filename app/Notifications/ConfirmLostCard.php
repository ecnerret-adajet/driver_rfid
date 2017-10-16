<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Driver;

class ConfirmLostCard extends Notification
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
        $driver_name = $this->driver->name;
        foreach($this->driver->trucks as $truck) {
            $truck_name = $truck->plate_number;
            $truck_id = $truck->id;
        }
        foreach($this->driver->haulers as $hauler) {
            $hauler_name = $hauler->name;
        }

        return (new MailMessage)
                ->success()
                ->subject('Truck Monitoring: Driver Reprinting Request')
                ->greeting('Good day!')
                ->line('A driver is requesting for ID re-printing for your approval. This will be deducted to respective hauler.')
                ->action('Confirm Now', url('/confirm/create/'.$this->driver->id.'/'.$truck_id))
                ->line('This is a generated notification from the system')
                ->markdown('vendor.notifications.lost', compact('driver_name','truck_name','hauler_name'));
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
