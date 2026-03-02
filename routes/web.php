<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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
});

require __DIR__.'/auth.php';
