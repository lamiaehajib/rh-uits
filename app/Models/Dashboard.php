<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;
    protected $table = '_dashboard';
    protected $fillable = ['iduser', 'idtach', 'idproject', 'idformation','idvente_objectif'];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function tache()
    {
        return $this->belongsTo(Tache::class, 'idtach');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'idproject');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'idformation');
    }
    public function VenteObjectif()
    {
        return $this->belongsTo(VenteObjectif::class, 'idvente_objectif');
    }
}

