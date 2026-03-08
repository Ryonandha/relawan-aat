<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Secretariat;
use App\Models\Event;
use App\Models\EventRegistration;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT DATA REGIONAL (Tanpa Alamat Spesifik)
        $sekrePurwokerto = Secretariat::create(['name' => 'Purwokerto']);
        $sekreYogyakarta = Secretariat::create(['name' => 'Yogyakarta']);
        $sekreSemarang   = Secretariat::create(['name' => 'Semarang']);

        // 2. BUAT ROLE
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin Pusat']);
        $roleAdminSekre = Role::firstOrCreate(['name' => 'Admin Sekre']);
        $roleRelawan    = Role::firstOrCreate(['name' => 'Relawan']);

        // 3. BUAT AKUN PENGURUS
        $superAdmin = User::create([
            'name' => 'Pengurus Pusat AAT',
            'email' => 'pusat@aat.or.id',
            'phone_number' => '081100001111',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($roleSuperAdmin);

        $adminPwt = User::create([
            'name' => 'Admin AAT Purwokerto',
            'email' => 'purwokerto@aat.or.id', 
            'phone_number' => '081200002222',
            'password' => Hash::make('password'),
            'secretariat_id' => $sekrePurwokerto->id,
        ]);
        $adminPwt->assignRole($roleAdminSekre);

        // 4. BUAT AKUN RELAWAN
        $relawan1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'phone_number' => '085711112222',
            'password' => Hash::make('password'),
            'sianas_id' => 'SIA-001',
            'secretariat_id' => $sekrePurwokerto->id,
        ]);
        $relawan1->assignRole($roleRelawan);

        // 5. BUAT DATA KEGIATAN DUMMY (Dengan Lokasi Spesifik)
        $event1 = Event::create([
            'title' => 'Bakti Sosial & Mengajar Panti Asuhan',
            'description' => 'Kegiatan rutin bulanan membagikan sembako dan bermain sambil belajar bersama adik-adik panti asuhan.',
            'event_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Panti Asuhan Harapan, Desa Grendeng, Purwokerto', // <- Lokasi Event
            'quota' => 15,
            'secretariat_id' => $sekrePurwokerto->id,
        ]);

        $eventMasaLalu = Event::create([
            'title' => 'Kopi Darat (Kopdar) Relawan AAT PWT',
            'description' => 'Pertemuan silaturahmi awal tahun seluruh relawan Anak-Anak Terang.',
            'event_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'start_time' => '15:00',
            'end_time' => '18:00',
            'location' => 'Cafe Kebun, Jl. Raya Baturraden KM 5', // <- Lokasi Event
            'quota' => 50,
            'secretariat_id' => $sekrePurwokerto->id,
        ]);

        // 6. BUAT DATA PENDAFTARAN
        EventRegistration::create([
            'event_id' => $event1->id,
            'user_id' => $relawan1->id,
            'status' => 'Registered',
        ]);

        EventRegistration::create([
            'event_id' => $eventMasaLalu->id,
            'user_id' => $relawan1->id,
            'status' => 'Attended',
        ]);
    }
}