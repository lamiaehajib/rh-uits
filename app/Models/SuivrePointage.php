<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles; // Muta2akkad wash Spatie kaytl3ab l'User model, machi l SuivrePointage model

class SuivrePointage extends Model
{
    use HasFactory, HasRoles; // A priori, HasRoles katkon f User model. Ila ma kenti katsta3mlahash hna, 7ayadha.

    protected $table = 'suivre_pointage'; // T'akkad beli had l'ism howa nefsou f db

    protected $fillable = [
        'iduser',
        'heure_arrivee',
        'heure_depart',
        'description',
        'localisation',    // Pour la localisation déterminée (UITS / mach UITS)
        'user_latitude',   // Latitude de l'utilisateur
        'user_longitude',  // Longitude de l'utilisateur
        'date_pointage',   // Date du pointage (pour le filtrage précis)
    ];

    protected $casts = [
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
        'date_pointage' => 'date', // Cast en date pour la manipulation aisée de la date seule
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
}