<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Tache extends Model
{
    use HasFactory,HasRoles,Notifiable;

    protected $fillable =  ['description', 'duree', 'status', 'date', 'iduser','datedebut','created_by', // Make sure these columns exist in your database
        'updated_by', ];

   

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'idtach');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the task.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
