<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'projet_id',
        
        'titre',
        'description',
        'date_heure',
        'lieu',
        'statut',
        'notes',
        'annule_par_user_id',
        'reprogramme_par_user_id',
        'confirme_par_user_id', // Ajoute cette ligne
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    // Relation avec le projet
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function confirmePar()
    {
        return $this->belongsTo(User::class, 'confirme_par_user_id');
    }

        public function reprogrammePar()
    {
        return $this->belongsTo(User::class, 'reprogramme_par_user_id');
    }

     public function annulePar()
    {
        return $this->belongsTo(User::class, 'annule_par_user_id');
    }
    

    // Scope pour les rendez-vous à venir
    public function scopeAvenir($query)
    {
        return $query->where('date_heure', '>', Carbon::now());
    }

    // Scope pour les rendez-vous d'aujourd'hui
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_heure', Carbon::today());
    }

    // Accessor pour formater la date
    public function getDateFormateeAttribute()
    {
        return $this->date_heure->format('d/m/Y H:i');
    }
}