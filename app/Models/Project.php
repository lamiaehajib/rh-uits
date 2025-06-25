<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory,HasRoles,Notifiable;

    protected $fillable = ['titre', 'nomclient', 'ville', 'bessoins', 'date_project'];

    protected static function boot()
    {
        parent::boot();

        // Ajouter la date actuelle à date_project si elle n'est pas fournie
        static::creating(function ($project) {
            if (empty($project->date_project)) {
                $project->date_project = now()->format('Y-m-d');
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'idproject');
    }
}
