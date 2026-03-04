<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Menampilkan daftar pengguna (Pusat lihat semua, Sekre lihat relawan kotanya)
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin Pusat')) {
            // Pusat: Tarik admin dan relawan dari SELURUH wilayah
            $admins = User::with(['secretariat', 'roles'])
                        ->whereHas('roles', function($q) {
                            $q->whereIn('name', ['Admin Sekre', 'Super Admin Pusat']);
                        })->get();

            $relawans = User::with(['secretariat', 'roles'])
                        ->whereHas('roles', function($q) {
                            $q->where('name', 'Relawan');
                        })->get();
                        
            $namaRegional = 'Semua Wilayah (Nasional)';
        } else {
            // Sekre: HANYA menarik relawan dari wilayahnya (Admin Sekre tidak perlu melihat admin lain)
            $admins = collect(); // Kosongkan tabel admin untuk Admin Sekre
            
            $relawans = User::with(['secretariat', 'roles'])
                        ->where('secretariat_id', $user->secretariat_id)
                        ->whereHas('roles', function($q) {
                            $q->where('name', 'Relawan');
                        })->get();
                        
            $namaRegional = $user->secretariat->name ?? 'Wilayah Tidak Diketahui';
        }

        // Kirim variabel $admins dan $relawans secara terpisah ke tampilan
        return view('admin.users.index', compact('admins', 'relawans', 'namaRegional'));
    }

    // Hanya Pusat yang bisa akses fungsi create dan store (sudah ada sebelumnya)
    public function create() {
        $secretariats = Secretariat::all();
        return view('admin.users.create', compact('secretariats'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'secretariat_id' => ['required', 'exists:secretariats,id'],
        ]);

        $user = User::create([
            'name' => $request->name, 'email' => $request->email,
            'password' => Hash::make($request->password), 'secretariat_id' => $request->secretariat_id,
        ]);
        $user->assignRole('Admin Sekre');

        return redirect()->route('admin.users.index')->with('success', 'Admin Sekre berhasil ditambahkan!');
    }

    // --- TAMBAHAN BARU: EDIT & UPDATE ---
    public function edit(User $user) {
        $secretariats = Secretariat::all();
        return view('admin.users.edit', compact('user', 'secretariats'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'secretariat_id' => 'required|exists:secretariats,id',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email, 'secretariat_id' => $request->secretariat_id]);

        if ($request->filled('password')) { // Jika password diisi, update passwordnya
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }
}