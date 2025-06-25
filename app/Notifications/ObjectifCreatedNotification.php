<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ObjectifCreatedNotification extends Notification
{
    use Queueable;

    private $objectif;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($objectif)
    {
        $this->objectif = $objectif;
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
            ->subject('Nouvel Objectif Assigné')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un nouvel objectif vous a été assigné :')
            ->line('**Type :** ' . $this->objectif->type)
            ->line('**Description :** ' . $this->objectif->description)
            ->line('**Date de début :** ' . $this->objectif->date)
            ->action('Voir l\'objectif', url('/objectifs/' . $this->objectif->id))
            ->line('Merci d\'utiliser notre application.');
    }
}
