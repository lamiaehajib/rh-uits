<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MagepreuveCreatedNotification extends Notification
{
    use Queueable;

    public $imagePreuve;

    /**
     * Create a new notification instance.
     */
    public function __construct($imagePreuve)
    {
        $this->imagePreuve = $imagePreuve;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle preuve créée')
            ->greeting('Bonjour,')
            ->line('Une nouvelle preuve a été créée.')
            ->line('Titre : ' . $this->imagePreuve->titre)
            ->line('Description : ' . ($this->imagePreuve->description ?? 'Aucune description.'))
            ->line('Date : ' . $this->imagePreuve->date)
            ->action('Voir la preuve', url('/image_preuve/' . $this->imagePreuve->id))
            ->line('Merci pour votre attention.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'titre' => $this->imagePreuve->titre,
            'description' => $this->imagePreuve->description,
            'date' => $this->imagePreuve->date,
        ];
    }
}
