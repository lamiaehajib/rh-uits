<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Tache; // Make sure to import your Tache model

class TacheUpdatedNotification extends Notification // implements ShouldQueue // Uncomment if you want to queue notifications
{
    use Queueable;

    protected $tache;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tache $tache)
    {
        $this->tache = $tache;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // You can choose multiple channels here.
        // For in-app notifications, 'database' is common.
        // For email, add 'mail'.
        return ['database']; 
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // This is for email notifications. Customize the content.
        return (new MailMessage)
                    ->subject('Mise à jour de votre tâche: ' . $this->tache->description)
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('La tâche "' . $this->tache->description . '" vous a été mise à jour.')
                    ->line('Nouveau statut: ' . ucfirst($this->tache->status))
                    ->action('Voir la tâche', url('/taches/' . $this->tache->id))
                    ->line('Merci d\'utiliser notre application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // This is for database notifications (stored in the 'notifications' table).
        return [
            'tache_id' => $this->tache->id,
            'description' => $this->tache->description,
            'new_status' => $this->tache->status,
            'updated_by' => auth()->user()->name ?? 'Système',
            'message' => 'La tâche "' . $this->tache->description . '" a été mise à jour au statut: ' . ucfirst($this->tache->status) . '.',
        ];
    }
}