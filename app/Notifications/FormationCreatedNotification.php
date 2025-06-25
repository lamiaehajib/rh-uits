<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormationCreatedNotification extends Notification
{
    use Queueable;

    public $formation;

    /**
     * Créer une nouvelle instance de notification.
     */
    public function __construct($formation)
    {
        $this->formation = $formation;
    }

    /**
     * Définir les canaux de notification.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Construire le message de notification pour l'email.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle Formation Créée')
            ->greeting('Bonjour,')
            ->line("Une nouvelle formation intitulée '{$this->formation->name}' a été créée.")
            ->line("Formateur : {$this->formation->nomformateur}")
            ->line("Date de la formation : {$this->formation->date}")
            ->line("Status : {$this->formation->status}")
            ->line('Veuillez vous inscrire ou participer à cette formation.')
            ->action('Voir la formation', url('/formations/' . $this->formation->id))
            ->line('Merci!');
    }

    /**
     * Définir la représentation de la notification pour les tableaux.
     */
    public function toArray($notifiable)
    {
        return [
            'formation_id' => $this->formation->id,
            'formation_title' => $this->formation->name,
        ];
    }
}
