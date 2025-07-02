<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles; // Vérifie si ce trait est vraiment nécessaire ici

class Objectif extends Model
{
    use HasFactory, Notifiable; // Retiré HasRoles si ce n'est pas le modèle Objectif qui gère les permissions

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
        'afaire',
       
        'created_by',
        'progress',
        'duree_value',
        'duree_type',
        'explanation_for_incomplete',
    ];

    /**
     * Définition de la relation avec les utilisateurs (plusieurs utilisateurs par objectif).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        // Relation Many-to-Many via la table pivot 'objectif_user'
        return $this->belongsToMany(User::class, 'objectif_user', 'objectif_id', 'user_id');
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