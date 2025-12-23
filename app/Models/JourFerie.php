<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourFerie extends Model
{
    use HasFactory;
 protected $table = 'jours_feries';
    protected $fillable = [
        'nom',
        'date',
        'annee',
        'type'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    // Récupérer tous les jours fériés pour une année donnée
    public static function getForYear($year)
    {
        return self::where('annee', $year)->pluck('date')->toArray();
    }

    // Vérifier si une date est un jour férié
    public static function isJourFerie($date)
    {
        return self::whereDate('date', $date)->exists();
    }
}