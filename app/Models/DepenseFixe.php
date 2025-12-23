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

    // Types dyal depenses fixes (sans les salaires hardcodés)
    public static $types = [
        'SALAIRE' => 'Salaire',
        'LOYER' => 'Loyer',
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

    // Relation m3a salarie (user li akhd le salaire)
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

    // Scope pour les salaires uniquement
    public function scopeSalaires($query)
    {
        return $query->where('type', 'SALAIRE');
    }

    // Get total depenses fixes par mois
    public static function totalParMois($mois)
    {
        return self::where('mois', $mois)
                   ->where('statut', 'payé')
                   ->sum('montant');
    }

    // Get total salaires par mois
    public static function totalSalairesParMois($mois)
    {
        return self::where('mois', $mois)
                   ->where('type', 'SALAIRE')
                   ->where('statut', 'payé')
                   ->sum('montant');
    }

    // Get le nom à afficher
    public function getNomAffichageAttribute()
    {
        if ($this->type === 'SALAIRE' && $this->salarie) {
            return 'Salaire de ' . $this->salarie->name;
        }
        return self::$types[$this->type] ?? $this->type;
    }
}