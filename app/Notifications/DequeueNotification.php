<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Queue;

class DequeueNotification extends Notification
{
    use Queueable;

    protected $queue;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
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
            ->success()
            ->subject('Truck Monitoring: Dequeue Request')
            ->greeting('Good day!')
            ->line('A driver is requesting for ID re-printing for your approval. This will be deducted to respective hauler.')
            ->action('Confirm Now', url('/replacements'))
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
