<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Lineup;

class ConfirmLineup extends Notification
{
    use Queueable;

    protected $lineup;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Lineup $lineup)
    {
        $this->lineup = $lineup;
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

        foreach($this->lineup->log->drivers as $driver) {
            $driver_name = $driver->name;
            foreach($driver->trucks as $truck) {
                $truck_name = $truck->plate_number;
            }
            foreach($driver->haulers as $hauler) {
                $hauler_name = $hauler->name;
            }
        }

        return (new MailMessage)
            ->success()
            ->subject('Truck Monitoring: Hustling Confirmation')
            ->greeting('Good day!')
            ->line('A truck is requesting for hustling, please confirm to proceed with this action')
            ->action('Confirm Now', url('/lineups/approval/'.$this->lineup->id))
            ->line('This is a generated notification from the system')
            ->markdown('vendor.notifications.lineups', compact('driver_name','truck_name','hauler_name'));
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
