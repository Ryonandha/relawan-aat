<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecretariatController;
use App\Models\Event;
use App\Models\Secretariat;
use App\Models\User;

Route::get('/', function () {
    // Menampilkan 3 kegiatan terdekat
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
        $totalRelawan = User::role('Relawan')->count();
        $totalSekre = Secretariat::count();
        $totalKegiatan = Event::count();
        $labelStat = "Nasional (Seluruh Regional)";
    } elseif ($user->hasRole('Admin Sekre')) {
        $totalRelawan = User::role('Relawan')->where('secretariat_id', $user->secretariat_id)->count();
        $totalSekre = 1;
        $totalKegiatan = Event::where('secretariat_id', $user->secretariat_id)->count();
        $labelStat = "Regional " . ($user->secretariat->name ?? '');
    } else {
        $totalRelawan = 0; $totalSekre = 0; $totalKegiatan = 0;
        $labelStat = "";
    }

    return view('dashboard', compact('totalRelawan', 'totalSekre', 'totalKegiatan', 'labelStat'));
})->middleware(['auth', 'verified'])->name('dashboard');

// KELOMPOK YANG WAJIB LOGIN
Route::middleware('auth')->group(function () {
    // Sisi Relawan & Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/kegiatan', [EventController::class, 'index'])->name('events.index');
    Route::post('/kegiatan/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::get('/riwayat-kegiatan', [EventController::class, 'history'])->name('events.history');
    Route::get('/sertifikat/download/{registration}', [EventController::class, 'downloadCertificate'])->name('certificate.download');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/riwayat-kegiatan', [EventController::class, 'history'])->name('events.history');
    Route::delete('/kegiatan/batal/{registration}', [EventController::class, 'cancel'])->name('events.cancel');
    
    // RUTE BERSAMA: Admin Sekre & Pusat
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

        // Manajemen Pengguna (Lihat, Edit, Hapus)
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // RUTE KHUSUS: Hanya Super Admin Pusat
    Route::middleware(['role:Super Admin Pusat'])->group(function () {
        // Tambah Pengguna Baru
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        
        // Manajemen Regional (Sekretariat)
        Route::get('/admin/secretariats', [SecretariatController::class, 'index'])->name('admin.secretariats.index');
        Route::post('/admin/secretariats', [SecretariatController::class, 'store'])->name('admin.secretariats.store');
        Route::delete('/admin/secretariats/{id}', [SecretariatController::class, 'destroy'])->name('admin.secretariats.destroy');
    });
});

require __DIR__.'/auth.php';