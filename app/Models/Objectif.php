<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
class Objectif extends Model
{
    use HasFactory,HasRoles,Notifiable;

    /**
     * Les attributs pouvant être assignés en masse.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'type',
        'description',
        'ca',
        'status',
        'afaire',
        'iduser',
        'created_by',
        'progress',
    ];

    /**
     * Définition de la relation avec l'utilisateur.
     * Chaque objectif appartient à un utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }


   

    /**
     * Get the attachments for the objectif.
     */
   

    /**
     * Get the user who created the objective.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
