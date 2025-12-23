<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepenseVariable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'depenses_variables';

    protected $fillable = [
        'type',
        'description',
        'montant',
        'date_depense',
        'mois',
        'categorie',
        'notes',
        'user_id',
        'beneficiaire_id',
        'justificatif'
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant' => 'decimal:2'
    ];

    // Types dyal depenses variables
    public static $types = [
        // Primes & Repos
        'PRIME_REPOS_KHALID' => 'Prime & Repos (Khalid)',
        'PRIME_REPOS_ABDELLATIF' => 'Prime & Repos (Abdellatif)',
        'PRIME_REPOS_SARA' => 'Prime & Repos (SARA)',
        'PRIME_REPOS_LAMIAE' => 'Prime & Repos (LAMIAE)',
        'PRIME_REPOS_GHIZLANE' => 'Prime & Repos (Ghizlane)',
        'PRIME_REPOS_SAAD' => 'Prime & Repos (SAAD)',
        'PRIME_REPOS_AHMED' => 'Prime & Repos (Ahmed)',
       
        'MR_KHALID' => 'Mr. Khalid',
        'MR_DAANOUNE' => 'Mr. Daanoune',
        'MR_HAIN' => 'Mr. Hain',
        'MR_KHADIJA' => 'Mr. Khadija',
        
        // Achat
        // s
        'Alimentation',
        'ACHAT_PCS' => 'Achat des PCS',
        'ACHAT_EQUIPEMENT' => 'Achat des équipements',
        
        // Autres
        'COPIER_CLES_PORTE' => 'Copier clés de la porte',
        'PRODUITS_MENAGES' => 'Produits des ménages',
        'PUBLICATIONS' => 'Publications',
        'FRAIS_BANQUE' => 'Frais de banque',
        'JAWAZ' => 'Jawaz',
        'AUTRE' => 'Autre'
    ];

    public static $categories = [
        'primes_repos' => 'Primes & Repos',
        'achats_equipements' => 'Achats & Équipements',
        'produits_menages' => 'Produits Ménages',
        'frais_bancaires' => 'Frais Bancaires',
        'publications' => 'Publications',
        'autres' => 'Autres'
    ];

    // Relation m3a user li dar l'enregistrement
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation m3a beneficiaire (ila kan prime)
    public function beneficiaire()
    {
        return $this->belongsTo(User::class, 'beneficiaire_id');
    }

    // Scope pour filter par mois
    public function scopeParMois($query, $mois)
    {
        return $query->where('mois', $mois);
    }

    // Scope pour filter par categorie
    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    // Get total depenses variables par mois
    public static function totalParMois($mois)
    {
        return self::where('mois', $mois)->sum('montant');
    }

    // Get total par categorie
    public static function totalParCategorie($mois, $categorie)
    {
        return self::where('mois', $mois)
                   ->where('categorie', $categorie)
                   ->sum('montant');
    }
}