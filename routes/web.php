<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Models\Event;
use App\Models\Secretariat;
use App\Models\User;
use App\Http\Controllers\UserController;

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
    // Menghitung data dari database
    $totalRelawan = User::role('Relawan')->count(); // Hanya hitung yang rolenya Relawan
    $totalSekre = Secretariat::count();
    $totalKegiatan = Event::count();

    return view('dashboard', compact('totalRelawan', 'totalSekre', 'totalKegiatan'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/kegiatan', [EventController::class, 'index'])->name('events.index');
    Route::post('/kegiatan/{event}/join', [EventController::class, 'join'])->name('events.join');
    Route::get('/riwayat-kegiatan', [EventController::class, 'history'])->name('events.history');
    Route::get('/sertifikat/download/{registration}', [EventController::class, 'downloadCertificate'])->name('certificate.download');

    Route::middleware(['role:Admin Sekre|Super Admin Pusat'])->group(function () {
        Route::get('/admin/events', [EventController::class, 'adminIndex'])->name('admin.events.index');
        Route::get('/admin/events/create', [EventController::class, 'create'])->name('admin.events.create');
        Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');
        Route::get('/admin/events/{event}/participants', [EventController::class, 'participants'])->name('admin.events.participants');
        Route::post('/admin/registrations/{registration}/check-in', [EventController::class, 'checkIn'])->name('admin.events.checkin');
    });

    Route::middleware(['auth', 'role:Super Admin Pusat'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
});
});

require __DIR__.'/auth.php';
