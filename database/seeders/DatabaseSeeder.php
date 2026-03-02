<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Secretariat;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan seeder Role dan User yang sudah kita buat
        $this->call([
            RoleAndUserSeeder::class,
        ]);

        // 2. Buat data Sekre Purwokerto
        $sekre = Secretariat::create([
            'name' => 'Sekre Purwokerto', 
            'city' => 'Purwokerto'
        ]);

        // 3. Buat data Kegiatan dummy
        Event::create([
            'title' => 'Pendampingan Belajar Anak Asuh', 
            'description' => 'Kegiatan pendampingan belajar rutin untuk anak asuh regional Purwokerto.', 
            'event_date' => '2026-03-15', 
            'start_time' => '09:00', 
            'end_time' => '12:00', 
            'quota' => 20, 
            'secretariat_id' => $sekre->id
        ]);
    }
}