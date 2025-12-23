<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldeConge extends Model
{
    use HasFactory;
 protected $table = 'solde_conges';
    protected $fillable = [
        'user_id',
        'annee',
        'total_jours',
        'jours_utilises',
        'jours_restants'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Initialiser le solde pour un utilisateur et une année
    public static function initSolde($userId, $year)
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'annee' => $year],
            [
                'total_jours' => 18,
                'jours_utilises' => 0,
                'jours_restants' => 18
            ]
        );
    }

    // Utiliser des jours de congé
    public function utiliserJours($nombreJours)
    {
        $this->jours_utilises += $nombreJours;
        $this->jours_restants = $this->total_jours - $this->jours_utilises;
        $this->save();
    }

    // Restituer des jours de congé (en cas d'annulation)
    public function restituerJours($nombreJours)
    {
        $this->jours_utilises -= $nombreJours;
        $this->jours_restants = $this->total_jours - $this->jours_utilises;
        $this->save();
    }
}