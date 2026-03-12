<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdreMission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordre_missions';

    protected $fillable = [
        'user_id', 'traite_par', 'destination', 'objet',
        'date_depart', 'date_retour',
        'moyen_transport', 'moyen_transport_autre',
        'frais_transport', 'frais_hebergement', 'frais_repas', 'frais_divers',
        'avance_demandee', 'statut', 'motif_refus', 'commentaire_admin',
        'notes_employe', 'frais_reels', 'avance_versee', 'solde_rembourse',
        'date_traitement', 'date_cloture',
    ];

    protected $casts = [
        'date_depart'       => 'datetime',
        'date_retour'       => 'datetime',
        'date_traitement'   => 'date',
        'date_cloture'      => 'date',
        'frais_transport'   => 'decimal:2',
        'frais_hebergement' => 'decimal:2',
        'frais_repas'       => 'decimal:2',
        'frais_divers'      => 'decimal:2',
        'avance_demandee'   => 'decimal:2',
        'frais_reels'       => 'decimal:2',
        'avance_versee'     => 'decimal:2',
        'solde_rembourse'   => 'decimal:2',
    ];

    public function employe()     { return $this->belongsTo(User::class, 'user_id'); }
    public function admin()       { return $this->belongsTo(User::class, 'traite_par'); }
    public function justificatifs() { return $this->hasMany(OrdreMissionJustificatif::class); }

    // ─── Accesseurs ──────────────────────────────────────────────
    public function getBudgetTotalAttribute(): float
    {
        return (float)$this->frais_transport + (float)$this->frais_hebergement
             + (float)$this->frais_repas     + (float)$this->frais_divers;
    }

    public function getDureeHeuresAttribute(): float
    {
        if (!$this->date_depart || !$this->date_retour) return 0;
        return round($this->date_depart->diffInMinutes($this->date_retour) / 60, 1);
    }

    public function getDureeFormatteeAttribute(): string
    {
        $h = $this->duree_heures;
        if ($h < 24) return $h . 'h';
        $j = floor($h / 24);
        $r = $h - ($j * 24);
        return $j . 'j' . ($r > 0 ? ' ' . $r . 'h' : '');
    }

    public function getIsMissionCourteAttribute(): bool
    {
        return $this->duree_heures < 24;
    }

    public function getStatutBadgeAttribute(): array
    {
        return match($this->statut) {
            'en_attente' => ['label' => 'En attente', 'color' => 'warning'],
            'approuve'   => ['label' => 'Approuvé',   'color' => 'success'],
            'refuse'     => ['label' => 'Refusé',     'color' => 'danger'],
            'annule'     => ['label' => 'Annulé',     'color' => 'secondary'],
            'cloture'    => ['label' => 'Clôturé',    'color' => 'info'],
            default      => ['label' => 'Inconnu',    'color' => 'dark'],
        };
    }

    public function scopeEnAttente($q)  { return $q->where('statut', 'en_attente'); }
    public function scopeApprouve($q)   { return $q->where('statut', 'approuve'); }
    public function scopeRefuse($q)     { return $q->where('statut', 'refuse'); }
    public function scopePourEmploye($q, int $id) { return $q->where('user_id', $id); }
}