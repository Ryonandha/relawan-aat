<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Menampilkan Tabel
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin Pusat')) {
            $admins = User::with(['secretariat', 'roles'])->whereHas('roles', function($q) {
                $q->whereIn('name', ['Admin Sekre', 'Super Admin Pusat']);
            })->get();
            $relawans = User::with(['secretariat', 'roles'])->whereHas('roles', function($q) {
                $q->where('name', 'Relawan');
            })->get();
            $namaRegional = 'Semua Wilayah (Nasional)';
        } else {
            $admins = collect(); // Kosongkan tabel admin untuk Admin Sekre
            $relawans = User::with(['secretariat', 'roles'])
                ->where('secretariat_id', $user->secretariat_id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Relawan');
                })->get();
            $namaRegional = $user->secretariat->name ?? 'Wilayah';
        }

        return view('admin.users.index', compact('admins', 'relawans', 'namaRegional'));
    }

    // Pusat Menambah Admin Sekre
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
            'name' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'secretariat_id' => $request->secretariat_id,
        ]);
        $user->assignRole('Admin Sekre');

        return redirect()->route('admin.users.index')->with('success', 'Admin Sekre berhasil ditambahkan!');
    }

    // Mengedit Pengguna
    public function edit(User $user) {
        $currentUser = auth()->user();

        // Jika Admin Sekre, tolak jika dia mencoba edit admin lain atau relawan di luar kotanya
        if ($currentUser->hasRole('Admin Sekre')) {
            if (!$user->hasRole('Relawan') || $user->secretariat_id != $currentUser->secretariat_id) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengedit pengguna ini.');
            }
        }

        $secretariats = Secretariat::all();
        return view('admin.users.edit', compact('user', 'secretariats'));
    }

    public function update(Request $request, User $user) {
        $currentUser = auth()->user();

        if ($currentUser->hasRole('Admin Sekre')) {
            if (!$user->hasRole('Relawan') || $user->secretariat_id != $currentUser->secretariat_id) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengedit pengguna ini.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'secretariat_id' => 'required|exists:secretariats,id',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email, 'secretariat_id' => $request->secretariat_id]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Menghapus Pengguna
    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak dapat menghapus akun yang sedang Anda pakai.');
        }

        if ($currentUser->hasRole('Super Admin Pusat')) {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus!');
        }

        if ($currentUser->hasRole('Admin Sekre')) {
            if ($user->hasRole('Relawan') && $user->secretariat_id == $currentUser->secretariat_id) {
                $user->delete();
                return redirect()->route('admin.users.index')->with('success', 'Akun relawan berhasil dihapus!');
            }
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk menghapus pengguna ini.');
    }
}