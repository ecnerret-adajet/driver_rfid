<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Driver;
use App\Truck;

class DisapprovedDriver extends Notification
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

        return (new MailMessage)
            ->error()
            ->subject('Truck Monitoring: Disapproved Notification')
            ->greeting('Good day!')
            ->line($this->driver->name. ' has been disapproved by approver. You may review again or cancel the request')
            ->action('Check Now', url('/drivers/disapproved/'.$this->driver->id))
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
