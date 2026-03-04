<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Secretariat; // <- Tambahkan Model Secretariat
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Buat Data Secretariat Dummy Terlebih Dahulu
        $sekrePurwokerto = Secretariat::create([
            'name' => 'Purwokerto',
        ]);

        // 1. Buat Role
        $roleSuperAdmin = Role::create(['name' => 'Super Admin Pusat']);
        $roleAdminSekre = Role::create(['name' => 'Admin Sekre']);
        $roleRelawan = Role::create(['name' => 'Relawan']);

        // 2. Buat Akun Dummy Super Admin (Pusat)
        $superAdmin = User::create([
            'name' => 'Admin Pusat AAT',
            'email' => 'pusat@aat.or.id',
            'password' => Hash::make('password123'),
        ]);
        $superAdmin->assignRole($roleSuperAdmin);

        // 3. Buat Akun Dummy Admin Sekre (Purwokerto)
        $adminSekre = User::create([
            'name' => 'Admin Purwokerto',
            'email' => 'purwokerto@gmail.com', 
            'password' => bcrypt('password'),
            'secretariat_id' => $sekrePurwokerto->id, // <- Ambil ID otomatis dari sekre yang baru dibuat di atas
        ]);
        $adminSekre->assignRole($roleAdminSekre);

        // 4. Buat Akun Dummy Relawan
        $relawan = User::create([
            'name' => 'Relawan Dummy',
            'email' => 'relawan@gmail.com',
            'password' => Hash::make('password123'),
            'sianas_id' => 'SIA-12345'
        ]);
        $relawan->assignRole($roleRelawan);
    }
}