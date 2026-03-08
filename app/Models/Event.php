<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // TAMBAHKAN 'location' KE DALAM FILLABLE
    protected $fillable = [
        'title', 
        'description', 
        'event_date', 
        'start_time', 
        'end_time', 
        'location',     // <- Ini wajib ada agar lokasi bisa disimpan
        'quota', 
        'secretariat_id', 
        'cover_image'
    ];

    public function secretariat()
    {
        return $this->belongsTo(Secretariat::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}