<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'type', // presence, absence, conge
        'justificatif',
        'justificatif_file',
        'justificatif_valide',
        'justificatif_soumis_at',
    ];
    
    protected $casts = [
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
        'date_pointage' => 'date',
        'justificatif_valide' => 'boolean',
        'justificatif_soumis_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
    
    /**
     * Vérifier si c'est une absence
     */
    public function isAbsence()
    {
        return $this->type === 'absence';
    }
    
    /**
     * Vérifier si c'est un congé
     */
    public function isConge()
    {
        return $this->type === 'conge';
    }
    
    /**
     * Vérifier si c'est une présence
     */
    public function isPresence()
    {
        return $this->type === 'presence';
    }
    
    /**
     * Vérifier si le justificatif est soumis
     */
    public function hasJustificatif()
    {
        return !empty($this->justificatif);
    }
    
    /**
     * Vérifier si le justificatif est validé
     */
    public function isJustificatifValide()
    {
        return $this->justificatif_valide === true;
    }
    
    /**
     * Obtenir le statut du justificatif (uniquement pour absences)
     */
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
}