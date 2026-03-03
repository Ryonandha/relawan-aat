<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function secretariat()
    {
        return $this->belongsTo(Secretariat::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
    protected $fillable = [
        'title', 'description', 'cover_image', 'event_date', // tambahkan cover_image di sini
        'start_time', 'end_time', 'quota', 'secretariat_id'
    ];
}