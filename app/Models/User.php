<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'tele',
        'code',
        'email',
        'poste',
        'salaire',
        'adresse',
        'repos',
        'password',
        'is_active',
        'last_login_at',
        'login_count',
        'societe_name',
        'type_client',
        'two_factor_expires_at',
    ];

    public function projets()
    {
        // This defines the many-to-many relationship with the Projet model.
        // It tells Laravel to use the 'projet_user' pivot table to link users and projects.
        return $this->belongsToMany(Projet::class, 'projet_user', 'user_id', 'projet_id');
    }

    public function taches()
    {
        return $this->hasMany(Tache::class, 'iduser');
    }

    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'formation_user');
    }

    public function suiviPointages()
    {
        return $this->hasMany(SuivrePointage::class, 'iduser');
    }

    public function image_preuve()
    {
        return $this->hasMany(ImagePreuve::class, 'iduser');
    }

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'iduser');
    }
    
    public function VenteObjectif()
    {
        return $this->hasMany(VenteObjectif::class, 'iduser');
    }
    
    public function reclamation()
    {
        return $this->hasMany(reclamation::class, 'iduser');
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    // Relations pour le système de congés
    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    public function soldeConges()
    {
        return $this->hasMany(SoldeConge::class);
    }

    public function congesTraites()
    {
        return $this->hasMany(Conge::class, 'traite_par');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
       
    ];
    
}