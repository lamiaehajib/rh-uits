<?php

namespace App\Notifications;

use App\Models\Avancement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AvancementCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $avancement;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Avancement $avancement)
    {
        $this->avancement = $avancement;
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
        $avancement = $this->avancement;
        
        // On s'assure que le projet est chargé pour accéder à son nom
        $projetNom = $avancement->projet ? $avancement->projet->titre : 'Projet Inconnu';
        
        return (new MailMessage)
                    ->subject('Nouvel Avancement dans Votre Projet : ' . $projetNom)
                    ->greeting('Cher Client,')
                    ->line('Une nouvelle étape d\'avancement a été enregistrée pour votre projet **"' . $projetNom . '"**.')
                    ->line('**Étape :** ' . $avancement->etape)
                    ->line('**Description :** ' . $avancement->description)
                    ->line('**Pourcentage d\'achèvement :** ' . $avancement->pourcentage . '%')
                    ->line('**Statut :** ' . $avancement->statut)
                    ->action('Voir les Détails de l\'Avancement', route('client.avancements.show', [$avancement->projet_id, $avancement->id])) // Assurez-vous que cette route existe pour le client
                    ->line('Merci de consulter votre espace client pour plus de détails.');
    }
}