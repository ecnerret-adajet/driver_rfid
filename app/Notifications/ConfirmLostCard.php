<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Driver;
use App\Lost;

class ConfirmLostCard extends Notification
{
    use Queueable;

    protected $driver;
    protected $lost;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Driver $driver, Lost $lost)
    {
        $this->driver = $driver;
        $this->lost = $lost;
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
        $truck_name = $this->driver->truck->plate_number;
        $hauler_name = $this->driver->hauler->name;
        $reason = $this->lost->reason;

        return (new MailMessage)
                ->success()
                ->subject('Truck Monitoring: Driver Reprinting Request')
                ->greeting('Good day!')
                ->line('A driver is requesting for ID re-printing for your approval. This will be deducted to respective hauler.')
                ->action('Confirm Now', url('/confirm/create/'.$this->driver->id.'/'.$this->driver->truck->id))
                ->line('This is a generated notification from the system')
                ->markdown('vendor.notifications.lost', compact('driver_name','truck_name','hauler_name','reason'));
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
