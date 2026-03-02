<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $guarded = []; // Mengizinkan insert data ke semua kolom

    // Relasi balik ke User (Relawan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi balik ke Event (Kegiatan)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}