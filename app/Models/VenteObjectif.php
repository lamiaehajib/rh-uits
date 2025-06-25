<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
class VenteObjectif extends Model
{
    use HasFactory,HasRoles;
    protected $table = 'vente_objectif';
    protected $fillable = ['date','description','Quantitépc', 'prixachat','totalachat', 'prixvendu', 'Quantitévendu', 'totalvendu','marge', 'enstock','iduser'];


    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'idvente_objectif');
    }

    
}

