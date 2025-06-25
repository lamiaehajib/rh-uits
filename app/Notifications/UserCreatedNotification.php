<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreatedNotification extends Notification
{
    private $email;
    private $password;
    private $siteUrl;

    public function __construct($email, $password, $siteUrl)
    {
        $this->email = $email;
        $this->password = $password;
        $this->siteUrl = $siteUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre compte a été créé')
            ->greeting('Bonjour !')
            ->line('Votre compte a été créé avec succès.')
            ->line('Email : ' . $this->email)
            ->line('Mot de passe : ' . $this->password)
            ->action('Accéder au site', $this->siteUrl)
            ->line('Merci d\'utiliser notre application !');
    }
}
