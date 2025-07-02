<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    // الأعمدة التي يمكن تعيين قيم لها بشكل جماعي (mass assignable)
    protected $fillable = [
        'name',
        'status',
        'nomformateur',
        'date',
        'file_path',
        'statut',        // تم التأكد من وجوده في الهجرة
        'nombre_heures', // تم التأكد من وجوده في الهجرة
        'nombre_seances',// تم التأكد من وجوده في الهجرة
        'prix',          // تم التأكد من وجوده في الهجرة
        'duree',   
        'duree_unit',       // تم التأكد من وجوده في الهجرة
        'created_by',    // **تم التأكد من وجوده في الهجرة الآن**
         'updated_by',
    ];

    /**
     * الحصول على المستخدمين المرتبطين بالتكوين (علاقة Many-to-Many).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'formation_user', 'formation_id', 'user_id');
    }

    /**
     * الحصول على لوحات التحكم المرتبطة بهذا التكوين (علاقة One-to-Many).
     */
    public function dashboards()
    {
        // تأكد أن 'idformation' هو اسم المفتاح الخارجي في جدول 'dashboards' الذي يشير إلى 'formations'
        return $this->hasMany(Dashboard::class, 'idformation');
    }

    /**
     * الحصول على المستخدم الذي أنشأ هذا التكوين.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

     public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}