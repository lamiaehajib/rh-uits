<?php

namespace App\Notifications;

use App\Models\Reclamation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReclamationCreatedNotification extends Notification
{
    use Queueable;

    public $reclamation;

    public function __construct(Reclamation $reclamation)
    {
        $this->reclamation = $reclamation;
    }

    /**
     * Les canaux de notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Notification par email et enregistrement en base de données
    }

    /**
     * Notification par email.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle Réclamation Créée')
            ->line('Une nouvelle réclamation a été créée par : ' . $this->reclamation->user->name)
            ->line('Titre : ' . $this->reclamation->titre)
            ->line('Description : ' . $this->reclamation->description)
            ->action('Voir la réclamation', url(route('reclamations.show', $this->reclamation->id)))
            ->line('Merci de vérifier cette réclamation dès que possible.');
    }

    /**
     * Notification enregistrée en base de données.
     *
     * @param  mixed  $notifiable
     * @return array
     */
   
}
