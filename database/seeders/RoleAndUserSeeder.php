<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
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
        $adminPurwokerto = User::create([
            'name' => 'Admin Sekre Purwokerto',
            'email' => 'purwokerto@aat.or.id',
            'password' => Hash::make('password123'),
        ]);
        $adminPurwokerto->assignRole($roleAdminSekre);

        // 4. Buat Akun Dummy Relawan
        $relawan = User::create([
            'name' => 'Relawan Dummy',
            'email' => 'relawan@gmail.com',
            'password' => Hash::make('password123'),
            'sianas_id' => 'SIA-12345' // Contoh integrasi ID SIANAS nantinya
        ]);
        $relawan->assignRole($roleRelawan);
    }
}