<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avancement extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'projet_id',
        'etape',
        'description',
        'pourcentage',
        'statut',
        'date_prevue',
        'date_realisee',
        'commentaires',
        'fichiers'
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'date_realisee' => 'date',
        'pourcentage' => 'integer'
    ];

    // Relation avec le projet
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    // Scope pour les étapes terminées
    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminé');
    }

    // Scope pour les étapes en cours
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en cours');
    }

    // Accessor pour la couleur selon le statut
    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            'en cours' => 'primary',
            'terminé' => 'success',
            'bloqué' => 'danger',
            default => 'secondary'
        };
    }

    // Accessor pour savoir si l'étape est en retard
    public function getEnRetardAttribute()
    {
        if ($this->date_prevue && $this->statut !== 'terminé') {
            return $this->date_prevue->isPast();
        }
        return false;
    }
}