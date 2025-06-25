<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectCreatedNotification extends Notification
{
    use Queueable;

    public $project;

    /**
     * Créer une nouvelle instance de notification.
     */
    public function __construct($project)
    {
        $this->project = $project;
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
            ->subject('Nouveau Projet Créé')
            ->greeting('Bonjour,')
            ->line("Un nouveau projet intitulé '{$this->project->titre}' a été créé.")
            ->line("Client : {$this->project->nomclient}")
            ->line("Ville : {$this->project->ville}")
            ->line('Veuillez vous connecter pour voir les détails.')
            ->action('Voir le projet', url('/projects/' . $this->project->id))
            ->line('Merci!');
    }
    public function toArray($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            'project_title' => $this->project->titre,
        ];
    }
}
