<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
class ImagePreuve extends Model
{
    use HasFactory,HasRoles,Notifiable;

    // Indiquer la table associée (si elle n'est pas le nom pluriel du modèle)
    protected $table = 'image_preuve';

    // Spécifier les colonnes qui peuvent être massivement assignées
    protected $fillable = [
        'titre',
        'description',
        'media',
        'date',
        'iduser'
    ];

    // Spécifier la relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
}
