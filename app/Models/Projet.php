<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'user_id',
        'date_debut',
        'date_fin',
        'fichier',
        'statut_projet'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    // Relation avec User (Client)
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec les rendez-vous
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    // Relation avec l'avancement
    public function avancements()
    {
        return $this->hasMany(Avancement::class);
    }

    // Scope pour filtrer par statut
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut_projet', $statut);
    }

    // Accessor pour le statut avec couleur
    public function getStatutColorAttribute()
    {
        return match($this->statut_projet) {
            'en cours' => 'warning',
            'terminé' => 'success',
            'en attente' => 'info',
            'annulé' => 'danger',
            default => 'secondary'
        };
    }
}