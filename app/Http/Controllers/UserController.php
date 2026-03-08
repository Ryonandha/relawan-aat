<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // 1. Menampilkan Tabel Pengguna
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Tangkap kata kunci pencarian dari URL
        $search = $request->input('search');

        if ($user->hasRole('Super Admin Pusat')) {
            // --- SUPER ADMIN: BISA LIHAT SEMUA ---
            
            // 1. Ambil Admin dengan Search & Pagination
            $admins = User::with(['secretariat', 'roles'])
                ->whereHas('roles', function($q) {
                    $q->whereIn('name', ['Admin Sekre', 'Super Admin Pusat']);
                })
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->paginate(10, ['*'], 'admin_page') // Pagination khusus Admin
                ->appends(['search' => $search]);   // Bawa keyword search ke halaman berikutnya

            // 2. Ambil Relawan dengan Search & Pagination
            $relawans = User::with(['secretariat', 'roles'])
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Relawan');
                })
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('sianas_id', 'like', "%{$search}%");
                    });
                })
                ->paginate(10, ['*'], 'relawan_page') // Pagination khusus Relawan
                ->appends(['search' => $search]);

            $namaRegional = 'Semua Wilayah (Nasional)';

        } else {
            // --- ADMIN SEKRE: HANYA LIHAT REGIONALNYA SAJA ---
            
            $admins = collect(); // Kosong untuk Admin Sekre
            
            $relawans = User::with(['secretariat', 'roles'])
                ->where('secretariat_id', $user->secretariat_id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Relawan');
                })
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('sianas_id', 'like', "%{$search}%");
                    });
                })
                ->paginate(10, ['*'], 'relawan_page')
                ->appends(['search' => $search]);

            $namaRegional = $user->secretariat->name ?? 'Wilayah';
        }

        return view('admin.users.index', compact('admins', 'relawans', 'namaRegional', 'search'));
    }

    // 2. Pusat Menambah Admin Sekre
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

    // 3. Menampilkan Form Edit Pengguna
    public function edit(User $user) {
        $currentUser = auth()->user();

        if ($currentUser->hasRole('Admin Sekre')) {
            if (!$user->hasRole('Relawan') || $user->secretariat_id != $currentUser->secretariat_id) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengedit pengguna ini.');
            }
        }

        $secretariats = Secretariat::all();
        return view('admin.users.edit', compact('user', 'secretariats'));
    }

    // 4. Memproses Update Pengguna (Termasuk ID SIANAS)
    public function update(Request $request, User $user) {
        $currentUser = auth()->user();

        // Keamanan: Admin Sekre hanya boleh edit relawan di kotanya
        if ($currentUser->hasRole('Admin Sekre')) {
            if (!$user->hasRole('Relawan') || $user->secretariat_id != $currentUser->secretariat_id) {
                abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengedit pengguna ini.');
            }
        }

        // Aturan validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'secretariat_id' => 'required|exists:secretariats,id',
        ];

        // Tambah aturan sianas_id JIKA yang mengedit adalah Super Admin Pusat
        if ($currentUser->hasRole('Super Admin Pusat')) {
            $rules['sianas_id'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        // Siapkan data yang akan diupdate
        $dataToUpdate = [
            'name' => $request->name, 
            'email' => $request->email, 
            'secretariat_id' => $request->secretariat_id
        ];

        // Masukkan ID SIANAS ke dalam update HANYA jika yang login adalah Pusat
        if ($currentUser->hasRole('Super Admin Pusat') && $request->has('sianas_id')) {
            $dataToUpdate['sianas_id'] = $request->sianas_id;
        }

        // Jalankan Update
        $user->update($dataToUpdate);

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    // 5. Menghapus Pengguna
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