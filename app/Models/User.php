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
    
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

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
        'adresse',
        'repos',
        'password',
        'is_active',
        'last_login_at',
        'login_count',
        'societe_name',
        'type_client',
    ];

    public function projects()
{
    return $this->belongsToMany(Project::class, 'projet_user');
}

  public function projets()
    {
        return $this->hasMany(Projet::class);
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
    ];
    
}
