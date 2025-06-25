<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TacheCreatedNotification extends Notification
{
    use Queueable;

    protected $tache;

    public function __construct($tache)
    {
        $this->tache = $tache;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle Tâche Créée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle tâche a été assignée à vous :')
            ->line('Description : ' . $this->tache->description)
            ->line('Date de début : ' . $this->tache->datedebut)
            ->action('Voir la Tâche', url('/taches/' . $this->tache->id))
            ->line('Merci d\'utiliser notre application !');
    }
}
