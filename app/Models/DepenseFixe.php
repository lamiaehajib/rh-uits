<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepenseFixe extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'depenses_fixes';

    protected $fillable = [
        'type',
        'description',
        'montant',
        'date_depense',
        'mois',
        'statut',
        'notes',
        'user_id',
        'salarie_id'
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant' => 'decimal:2'
    ];

    // Types dyal depenses fixes
    public static $types = [
        'SALAIRE_KHALID' => 'Salaire de Khalid',
        'SALAIRE_ABDELLATIF' => 'Salaire de Abdellatif',
        'SALAIRE_SARA' => 'Salaire de Sara',
      
        'SALAIRE_GHIZLANE' => 'Salaire de Ghizlane',
        'SALAIRE_AHMED' => 'Salaire de Ahmed',
       
        'LOYER' => 'Loyer ok',
        'FEMME_MENAGE' => 'Femme de ménage',
        'CONNEXION_ORANGE' => 'Connexion Orange',
        'LYDEC' => 'LYDEC',
        'ABONNEMENT_IAM' => 'Abonnement IAM',
        'ZAKARIA_MEDIALIK' => 'Zakaria Medialik',
        'CNSS' => 'CNSS',
        'AUTRE' => 'Autre'
    ];

    // Relation m3a user li dar l'enregistrement
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation m3a salarie ila kan salaire
    public function salarie()
    {
        return $this->belongsTo(User::class, 'salarie_id');
    }

    // Scope pour filter par mois
    public function scopeParMois($query, $mois)
    {
        return $query->where('mois', $mois);
    }

    // Scope pour filter par statut
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // Get total depenses fixes par mois
    public static function totalParMois($mois)
    {
        return self::where('mois', $mois)
                   ->where('statut', 'payé')
                   ->sum('montant');
    }
}