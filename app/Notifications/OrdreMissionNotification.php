<?php

namespace App\Notifications;

use App\Models\OrdreMission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrdreMissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public OrdreMission $mission,
        public string $type  // 'nouvelle_demande' | 'approuve' | 'refuse'
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return match($this->type) {
            'nouvelle_demande' => (new MailMessage)
                ->subject('📋 Nouvelle demande d\'ordre de mission')
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Une nouvelle demande d\'ordre de mission a été soumise par **' . $this->mission->employe->name . '**.')
                ->line('**Destination :** ' . $this->mission->destination)
                ->line('**Dates :** du ' . $this->mission->date_depart->format('d/m/Y') . ' au ' . $this->mission->date_retour->format('d/m/Y'))
                ->line('**Avance demandée :** ' . number_format($this->mission->avance_demandee, 2) . ' MAD')
                ->action('Traiter la demande', route('ordre-missions.show', $this->mission))
                ->line('Merci de traiter cette demande dans les plus brefs délais.'),

            'approuve' => (new MailMessage)
                ->subject('✅ Votre ordre de mission a été approuvé')
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Votre demande d\'ordre de mission pour **' . $this->mission->destination . '** a été **approuvée**.')
                ->line('**Avance accordée :** ' . number_format($this->mission->avance_versee, 2) . ' MAD')
                ->when($this->mission->commentaire_admin, fn($mail) => $mail->line('**Commentaire :** ' . $this->mission->commentaire_admin))
                ->action('Voir les détails', route('ordre-missions.show', $this->mission)),

            'refuse' => (new MailMessage)
                ->subject('❌ Votre ordre de mission a été refusé')
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Votre demande d\'ordre de mission pour **' . $this->mission->destination . '** a été **refusée**.')
                ->line('**Motif :** ' . $this->mission->motif_refus)
                ->action('Voir les détails', route('ordre-missions.show', $this->mission)),

            default => (new MailMessage)->line('Mise à jour de votre ordre de mission.')
        };
    }

    public function toArray($notifiable): array
    {
        return [
            'type'           => $this->type,
            'mission_id'     => $this->mission->id,
            'destination'    => $this->mission->destination,
            'statut'         => $this->mission->statut,
            'employe_name'   => $this->mission->employe->name,
            'avance'         => $this->mission->avance_demandee,
        ];
    }
}