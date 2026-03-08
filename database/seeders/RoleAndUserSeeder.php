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
        // ==========================================
        // 1. BUAT DATA REGIONAL (SECRETARIAT)
        // ==========================================
        $sekrePurwokerto = Secretariat::create(['name' => 'Purwokerto']);
        $sekreYogyakarta = Secretariat::create(['name' => 'Yogyakarta']);
        $sekreSemarang   = Secretariat::create(['name' => 'Semarang']);

        // ==========================================
        // 2. BUAT ROLE (HAK AKSES)
        // ==========================================
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin Pusat']);
        $roleAdminSekre = Role::firstOrCreate(['name' => 'Admin Sekre']);
        $roleRelawan    = Role::firstOrCreate(['name' => 'Relawan']);

        // ==========================================
        // 3. BUAT AKUN PENGURUS (SUPER ADMIN & ADMIN SEKRE)
        // ==========================================
        
        // Super Admin Pusat (Tidak terikat regional tertentu)
        $superAdmin = User::create([
            'name' => 'Pengurus Pusat AAT',
            'email' => 'pusat@aat.or.id',
            'phone_number' => '081100001111',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($roleSuperAdmin);

        // Admin Regional Purwokerto
        $adminPwt = User::create([
            'name' => 'Admin AAT Purwokerto',
            'email' => 'purwokerto@aat.or.id', 
            'phone_number' => '081200002222',
            'password' => Hash::make('password'),
            'secretariat_id' => $sekrePurwokerto->id,
        ]);
        $adminPwt->assignRole($roleAdminSekre);

        // Admin Regional Yogyakarta
        $adminYogya = User::create([
            'name' => 'Admin AAT Yogyakarta',
            'email' => 'yogyakarta@aat.or.id', 
            'phone_number' => '081300003333',
            'password' => Hash::make('password'),
            'secretariat_id' => $sekreYogyakarta->id,
        ]);
        $adminYogya->assignRole($roleAdminSekre);


        // ==========================================
        // 4. BUAT AKUN RELAWAN (Dengan Data Lengkap)
        // ==========================================
        
        $relawan1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'phone_number' => '085711112222',
            'password' => Hash::make('password'),
            'sianas_id' => 'SIA-001',
            'secretariat_id' => $sekrePurwokerto->id,
        ]);
        $relawan1->assignRole($roleRelawan);

        $relawan2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'phone_number' => '085733334444',
            'password' => Hash::make('password'),
            'sianas_id' => 'SIA-002',
            'secretariat_id' => $sekrePurwokerto->id,
        ]);
        $relawan2->assignRole($roleRelawan);

        // Relawan Baru (Belum punya ID Sianas)
        $relawan3 = User::create([
            'name' => 'Andi Darmawan',
            'email' => 'andi@gmail.com',
            'phone_number' => '085755556666',
            'password' => Hash::make('password'),
            'secretariat_id' => $sekreYogyakarta->id,
        ]);
        $relawan3->assignRole($roleRelawan);


        // ==========================================
        // 5. BUAT DATA KEGIATAN DUMMY
        // ==========================================
        
        $event1 = Event::create([
            'title' => 'Bakti Sosial & Mengajar Panti Asuhan',
            'description' => 'Kegiatan rutin bulanan membagikan sembako dan bermain sambil belajar bersama adik-adik panti asuhan.',
            'event_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Panti Asuhan Harapan, Purwokerto',
            'quota' => 15,
            'secretariat_id' => $sekrePurwokerto->id,
        ]);

        $event2 = Event::create([
            'title' => 'Pelatihan Jurnalistik Relawan',
            'description' => 'Pelatihan dasar menulis berita dan meliput kegiatan yayasan untuk dipublikasikan di sosial media.',
            'event_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'start_time' => '13:00',
            'end_time' => '16:00',
            'location' => 'Kampus UGM, Yogyakarta',
            'quota' => 30,
            'secretariat_id' => $sekreYogyakarta->id,
        ]);

        $eventMasaLalu = Event::create([
            'title' => 'Kopi Darat (Kopdar) Relawan AAT PWT',
            'description' => 'Pertemuan silaturahmi awal tahun seluruh relawan Anak-Anak Terang regional Purwokerto.',
            'event_date' => Carbon::now()->subDays(10)->format('Y-m-d'), // Tanggal di masa lalu
            'start_time' => '15:00',
            'end_time' => '18:00',
            'location' => 'Cafe Kebun, Purwokerto',
            'quota' => 50,
            'secretariat_id' => $sekrePurwokerto->id,
        ]);


        // ==========================================
        // 6. BUAT DATA PENDAFTARAN RELAWAN (REGISTRATION)
        // ==========================================
        
        // Budi daftar Event 1 (Status: Registered / Menunggu Hari H)
        EventRegistration::create([
            'event_id' => $event1->id,
            'user_id' => $relawan1->id,
            'status' => 'Registered',
        ]);

        // Siti daftar Event 1
        EventRegistration::create([
            'event_id' => $event1->id,
            'user_id' => $relawan2->id,
            'status' => 'Registered',
        ]);

        // Budi Hadir di Event Masa Lalu (Status: Attended -> Bisa Download Sertifikat)
        EventRegistration::create([
            'event_id' => $eventMasaLalu->id,
            'user_id' => $relawan1->id,
            'status' => 'Attended',
        ]);
        
        // Siti terdaftar di Event Masa Lalu tapi tidak hadir (Status: Registered)
        EventRegistration::create([
            'event_id' => $eventMasaLalu->id,
            'user_id' => $relawan2->id,
            'status' => 'Registered',
        ]);
    }
}