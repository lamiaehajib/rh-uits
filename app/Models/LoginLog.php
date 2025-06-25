<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    // Définir les colonnes modifiables
    protected $fillable = [
        'user_id',
        'logged_in_at',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'logged_in_at' => 'datetime',
    ];
    
}
