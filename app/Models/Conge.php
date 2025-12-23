<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_debut',
        'date_fin',
        'nombre_jours_demandes',
        'nombre_jours_ouvrables',
        'motif',
        'statut',
        'commentaire_admin',
        'traite_par',
        'traite_le'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'traite_le' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    // Scope pour les congés en attente
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // Scope pour les congés approuvés
    public function scopeApprouve($query)
    {
        return $query->where('statut', 'approuve');
    }
}