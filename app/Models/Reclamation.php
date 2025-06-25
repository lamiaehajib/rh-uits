<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity; // Make sure to import this

class Reclamation extends Model
{
    use HasFactory, LogsActivity;

    // ... (your existing fillable or guarded properties) ...
    protected $fillable = [
        'titre',
        'date',
        'description',
        'priority',
        'category',
        'status',
        'admin_notes',
        'iduser',
        'reference',
        'attachments',
        'resolved_at',
    ];

    // Define which attributes you want to log changes for
    // protected static $logFillable = true; // Logs changes for all fillable attributes
    protected static $logAttributes = ['titre', 'description', 'priority', 'category', 'status', 'admin_notes', 'iduser', 'resolved_at']; // Or specify relevant attributes

    // Optionally, specify the log name
    protected static $logName = 'reclamation';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Logs all fillable attributes
            // ->logOnly(['titre', 'description', 'status', 'priority', 'category', 'admin_notes']) // Or log specific attributes
            ->logOnlyDirty() // Only log changed attributes
            ->dontSubmitEmptyLogs(); // Don't log if nothing changed
    }

    // Define the relationship to the User model (if not already there)
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

}
