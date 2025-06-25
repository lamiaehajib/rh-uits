<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
class SuivrePointage extends Model
{
    use HasFactory,HasRoles;
        
    protected $table = 'suivre_pointage';
    protected $fillable = ['iduser', 'heure_arrivee', 'heure_depart', 'description','localisation'];

    protected $casts = [
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    
}