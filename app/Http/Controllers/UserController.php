<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Menampilkan daftar admin
    public function index()
    {
        // Ambil user yang memiliki role 'Admin Sekre' atau 'Super Admin Pusat'
        $admins = User::with('secretariat')
                    ->whereHas('roles', function($q) {
                        $q->whereIn('name', ['Admin Sekre', 'Super Admin Pusat']);
                    })->get();

        return view('admin.users.index', compact('admins'));
    }

    // Menampilkan form tambah admin baru
    public function create()
    {
        $secretariats = Secretariat::all();
        return view('admin.users.create', compact('secretariats'));
    }

    // Menyimpan data admin baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'secretariat_id' => ['required', 'exists:secretariats,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'secretariat_id' => $request->secretariat_id,
        ]);

        // Otomatis jadikan sebagai Admin Sekre
        $user->assignRole('Admin Sekre');

        return redirect()->route('admin.users.index')->with('success', 'Akun Admin Sekre berhasil ditambahkan!');
    }
}