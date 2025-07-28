<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Tache extends Model
{
    use HasFactory, Notifiable,HasRoles; 

    protected $fillable = [
        'description',
        'audio_description_path', 
        'duree',
        'status',
        'date',
     
        'datedebut',
        'date_fin_prevue',
        'created_by',
        'updated_by',
        'titre',
        'priorite',
        'retour',
    ];

    /**
     * Get the users assigned to the task.
     */
    public function users()
    {
        
        return $this->belongsToMany(User::class, 'tache_users', 'tache_id', 'user_id');
    }

    // ... باقي العلاقات والدوال الأخرى تبقى كما هي

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}