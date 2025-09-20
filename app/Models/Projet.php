<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 👈 1. استيراد SoftDeletes

class Projet extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'titre',
        'description',
        
        'date_debut',
        'date_fin',
        'fichier',
        'statut_projet'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    
    

      public function users()
{
    return $this->belongsToMany(User::class, 'projet_user');
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