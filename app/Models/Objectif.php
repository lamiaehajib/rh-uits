<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Objectif extends Model
{
    use HasFactory, HasRoles, Notifiable;

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
        // 'status', // Le champ 'status' a été supprimé de la base de données et donc du fillable
        'afaire',
        'iduser',
        'created_by',
        'progress',
        'duree_value', // Ajouté pour permettre l'assignation massive
        'duree_type',  // Ajouté pour permettre l'assignation massive
        'explanation_for_incomplete', // Ajouté pour permettre l'assignation massive
    ];

    /**
     * Définition de la relation avec l'utilisateur principal (celui à qui l'objectif est assigné).
     * Chaque objectif appartient à un utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    /**
     * Obtenir l'utilisateur qui a créé l'objectif.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
