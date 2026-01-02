<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuivrePointage extends Model
{
    use HasFactory;
    
    protected $table = 'suivre_pointage';
    
    protected $fillable = [
        'iduser',
        'heure_arrivee',
        'heure_depart',
        'description',
        'localisation',
        'user_latitude',
        'user_longitude',
        'date_pointage',
        'type',
        'justificatif',
        'justificatif_file',
        'justificatif_valide',
        'justificatif_soumis_at',
        'justificatif_retard',
        'justificatif_retard_file',
        'retard_justifie',
        'justificatif_retard_soumis_at',
    ];
    
    protected $casts = [
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
        'date_pointage' => 'date',
        'justificatif_valide' => 'boolean',
        'justificatif_soumis_at' => 'datetime',
        'retard_justifie' => 'boolean',
        'justificatif_retard_soumis_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
    
    public function isAbsence()
    {
        return $this->type === 'absence';
    }
    
    public function isConge()
    {
        return $this->type === 'conge';
    }
    
    public function isPresence()
    {
        return $this->type === 'presence';
    }
    
    public function hasJustificatif()
    {
        return !empty($this->justificatif);
    }
    
    public function isJustificatifValide()
    {
        return $this->justificatif_valide === true;
    }
    
    public function getJustificatifStatus()
    {
        if (!$this->isAbsence()) {
            return null;
        }
        
        if (!$this->hasJustificatif()) {
            return 'non_soumis';
        }
        
        if ($this->isJustificatifValide()) {
            return 'valide';
        }
        
        return 'en_attente';
    }
    
    /**
     * Vérifier si l'arrivée est en retard
     */
    public function isLate()
    {
        if (!$this->heure_arrivee || $this->type !== 'presence') {
            return false;
        }
        
        $arriveeTime = Carbon::parse($this->heure_arrivee);
        $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
        
        return $arriveeTime->greaterThan($expectedArrivee);
    }
    
    /**
     * Obtenir les minutes de retard
     */
    public function getRetardMinutes()
    {
        if (!$this->isLate()) {
            return 0;
        }
        
        $arriveeTime = Carbon::parse($this->heure_arrivee);
        $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
        
        return $arriveeTime->diffInMinutes($expectedArrivee);
    }
    
    /**
     * Vérifier si le justificatif de retard est soumis
     */
    public function hasJustificatifRetard()
    {
        return !empty($this->justificatif_retard);
    }
    
    /**
     * Obtenir le statut du justificatif de retard
     */
    public function getJustificatifRetardStatus()
    {
        if (!$this->isLate()) {
            return null;
        }
        
        if (!$this->hasJustificatifRetard()) {
            return 'non_soumis';
        }
        
        if ($this->retard_justifie) {
            return 'valide';
        }
        
        return 'en_attente';
    }
}