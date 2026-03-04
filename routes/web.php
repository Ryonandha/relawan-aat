<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Models\Event;
use App\Models\Secretariat;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecretariatController; // Tambahkan ini agar lebih rapi

Route::get('/', function () {
    // Mengambil 3 kegiatan terdekat yang belum lewat tanggalnya
    $latestEvents = Event::with('secretariat')
                         ->whereDate('event_date', '>=', now())
                         ->orderBy('event_date', 'asc')
                         ->take(3)
                         ->get();

    return view('welcome', compact('latestEvents'));
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('Super Admin Pusat')) {
        // Jika Pusat: Hitung SELURUH data nasional
        $totalRelawan = User::role('Relawan')->count();
        $totalSekre = Secretariat::count();
        $totalKegiatan = Event::count();
        $labelStat = "Nasional (Seluruh Regional)";
    } elseif ($user->hasRole('Admin Sekre')) {
        // Jika Sekre: Hitung HANYA data di regionalnya sendiri
        $totalRelawan = User::role('Relawan')->where('secretariat_id', $user->secretariat_id)->count();
        $totalSekre = 1; // Karena dia hanya memegang 1 sekre
        $totalKegiatan = Event::where('secretariat_id', $user->secretariat_id)->count();
        $labelStat = "Regional " . ($user->secretariat->name ?? '');
    } else {
        // Relawan biasa tidak melihat statistik ini
        $totalRelawan = 0; $totalSekre = 0; $totalKegiatan = 0;
        $labelStat = "";
    }

    return view('dashboard', compact('totalRelawan', 'totalSekre', 'totalKegiatan', 'labelStat'));
})->middleware(['auth', 'verified'])->name('dashboard');

// KELOMPOK RUTE YANG WAJIB LOGIN
Route::middleware('auth')->group(function () {
    
    // --- 1. RUTE UMUM & RELAWAN ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/kegiatan', [EventController::class, 'index'])->name('events.index');
    Route::post('/kegiatan/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::get('/riwayat-kegiatan', [EventController::class, 'history'])->name('events.history');
    Route::get('/sertifikat/download/{registration}', [EventController::class, 'downloadCertificate'])->name('certificate.download');

    // --- 2. RUTE BERSAMA (ADMIN SEKRE & SUPER ADMIN PUSAT) ---
    Route::middleware(['role:Admin Sekre|Super Admin Pusat'])->group(function () {
        // Manajemen Kegiatan
        Route::get('/admin/events', [EventController::class, 'adminIndex'])->name('admin.events.index');
        Route::get('/admin/events/create', [EventController::class, 'create'])->name('admin.events.create');
        Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');
        Route::get('/admin/events/{event}/participants', [EventController::class, 'participants'])->name('admin.events.participants');
        Route::post('/admin/registrations/{registration}/check-in', [EventController::class, 'checkIn'])->name('admin.events.checkin');
        Route::get('/admin/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
        Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
        Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');

        // Manajemen Pengguna Dasar (Lihat Daftar & Hapus Relawan)
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // --- 3. RUTE KHUSUS (HANYA SUPER ADMIN PUSAT) ---
    Route::middleware(['role:Super Admin Pusat'])->group(function () {
        // Sisa Manajemen Pengguna (Tambah & Edit Admin)
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');

        // Manajemen Regional (Sekretariat)
        Route::get('/admin/secretariats', [SecretariatController::class, 'index'])->name('admin.secretariats.index');
        Route::post('/admin/secretariats', [SecretariatController::class, 'store'])->name('admin.secretariats.store');
        Route::delete('/admin/secretariats/{id}', [SecretariatController::class, 'destroy'])->name('admin.secretariats.destroy');
    });
});

require __DIR__.'/auth.php';