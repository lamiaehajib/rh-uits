<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrdreMissionJustificatif extends Model
{
    protected $table = 'ordre_mission_justificatifs';

    protected $fillable = [
        'ordre_mission_id',
        'user_id',
        'nom_fichier',
        'chemin',
        'type_mime',
        'taille',
        'type_doc',
        'description',
    ];

    // ─── Relations ───────────────────────────────────────────────
    public function ordreMission()
    {
        return $this->belongsTo(OrdreMission::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Accesseurs ──────────────────────────────────────────────
    public function getUrlAttribute(): string
    {
        return Storage::url($this->chemin);
    }

    public function getTailleFormatAttribute(): string
    {
        $bytes = $this->taille;
        if ($bytes < 1024)       return $bytes . ' o';
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / 1048576, 2) . ' Mo';
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->type_mime, 'image/');
    }

    public function getIconAttribute(): string
    {
        if ($this->is_image) return 'fa-image text-success';
        return match(true) {
            str_contains($this->type_mime, 'pdf')  => 'fa-file-pdf text-danger',
            str_contains($this->type_mime, 'word') => 'fa-file-word text-primary',
            default                                => 'fa-file text-secondary',
        };
    }

    public function getLabelTypeAttribute(): string
    {
        return match($this->type_doc) {
            'bon_transport'  => '🚌 Bon transport',
            'facture_hotel'  => '🏨 Facture hôtel',
            'facture_repas'  => '🍽️ Facture repas',
            'ticket'         => '🎫 Ticket',
            default          => '📎 Autre',
        };
    }
}