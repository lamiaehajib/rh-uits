<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Had L-Notification ghadi ttsifat l-tous les clients lli f l-Project
class RendezVousCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rendezVous;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Kan9olo l-Laravel bghina nssiftoha gha f mail
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Kanakhdo l-RendezVous lli tcreé
        $rendezVous = $this->rendezVous;
        
        // Kanformatiw la date w l'heure bach ybano mzian
        $dateHeure = $rendezVous->date_heure->format('d/m/Y H:i');
        
        // Kancreéw l-Message lli ghaywsel l-Client
        return (new MailMessage)
                    ->subject('Nouveau intervention Programmé : ' . $rendezVous->titre)
                    ->greeting('Bonjour,')
                    ->line('Un nouveau intervention a été programmé pour votre projet **"' . $rendezVous->projet->titre . '"**.')
                    ->line('Voici les détails :')
                    ->line('**Titre :** ' . $rendezVous->titre)
                    ->line('**Date et Heure :** ' . $dateHeure)
                    ->line('**Lieu :** ' . ($rendezVous->lieu ?? 'Non spécifié'))
                    ->action('Voir l`intervention', route('client.client.rendez-vous.show', $rendezVous->id)) // Assurez-vous que cette route existe
                    ->line('Merci de vérifier les détails et de le confirmer si nécessaire.');
    }

    // ... autres méthodes
}