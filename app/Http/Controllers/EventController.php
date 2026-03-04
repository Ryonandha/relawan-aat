<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barrier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Fungsi untuk menampilkan daftar kegiatan
   public function index()
    {
        $userSekreId = auth()->user()->secretariat_id;

        // Mengambil event yang HANYA milik sekre relawan tersebut
        // dan mengurutkan berdasarkan tanggal terbaru
        $events = Event::with('secretariat')
                    ->where('secretariat_id', $userSekreId)
                    ->orderBy('event_date', 'asc')
                    ->get();

        return view('events.index', compact('events'));
    }
    // Fungsi untuk memproses pendaftaran relawan
    public function join(Event $event)
    {
        $userId = Auth::id(); // Mengambil ID relawan yang sedang login

        // Cek apakah relawan sudah pernah mendaftar di kegiatan ini
        $isRegistered = EventRegistration::where('user_id', $userId)
                            ->where('event_id', $event->id)
                            ->exists();

        if ($isRegistered) {
            return back()->with('error', 'Kamu sudah terdaftar di kegiatan ini!');
        }

        // Cek apakah kuota masih tersedia (opsional tapi penting)
        $pendaftarSaatIni = EventRegistration::where('event_id', $event->id)->count();
        if ($pendaftarSaatIni >= $event->quota) {
            return back()->with('error', 'Maaf, kuota kegiatan ini sudah penuh.');
        }

        // Simpan ke tabel event_registrations
        EventRegistration::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'is_attended' => false, // Default belum hadir
        ]);

        return back()->with('success', 'Berhasil mendaftar kegiatan!');
    }
    // Tambahkan di bagian atas controller
public function adminIndex()
    {
        // Mengecek apakah yang login adalah Super Admin Pusat
        if (auth()->user()->hasRole('Super Admin Pusat')) {
            // Pusat melihat SEMUA kegiatan dari semua sekre
            $events = Event::with(['secretariat'])
                        ->withCount('registrations')
                        ->orderBy('event_date', 'desc')
                        ->get();
            
            $namaRegional = 'Semua Regional (Pusat)';
        } else {
            // Admin Sekre HANYA melihat kegiatan di wilayahnya
            $events = Event::withCount('registrations')
                        ->where('secretariat_id', auth()->user()->secretariat_id)
                        ->orderBy('event_date', 'desc')
                        ->get();
            
            $namaRegional = auth()->user()->secretariat->name ?? 'Wilayah Tidak Diketahui';
        }

        // Kirim data kegiatan dan nama regional ke tampilan
        return view('admin.events.index', compact('events', 'namaRegional'));
    }

public function create()
{
    return view('admin.events.create');
}

public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            // Simpan foto ke folder storage/app/public/event_covers
            $imagePath = $request->file('cover_image')->store('event_covers', 'public');
        }

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'cover_image' => $imagePath, // Masukkan path foto ke database
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'quota' => $request->quota,
            'secretariat_id' => auth()->user()->secretariat_id,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Kegiatan berhasil dibuat!');
    }
public function participants(Event $event)
{
    // Memastikan Admin hanya bisa melihat pendaftar di sekrenya sendiri
    if (auth()->user()->role !== 'Super Admin Pusat' && $event->secretariat_id !== auth()->user()->secretariat_id) {
        abort(403);
    }

    // Mengambil data pendaftar beserta data user-nya
    $participants = EventRegistration::with('user')
                    ->where('event_id', $event->id)
                    ->get();

    return view('admin.events.participants', compact('event', 'participants'));
}
public function checkIn(EventRegistration $registration)
{
    // Balikkan status kehadiran
    $registration->update([
        'is_attended' => !$registration->is_attended 
    ]);

    return back()->with('success', 'Status kehadiran berhasil diperbarui!');
}
public function history()
{
    // Mengambil riwayat pendaftaran milik user yang sedang login
    $myRegistrations = EventRegistration::with('event.secretariat')
                        ->where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('events.history', compact('myRegistrations'));
}
public function downloadCertificate(EventRegistration $registration)
{
    // 1. Keamanan: Pastikan yang download adalah pemilik akun
    if ($registration->user_id !== auth()->id()) {
        abort(403, 'Akses ditolak.');
    }

    // 2. Keamanan: Pastikan sudah ditandai HADIR oleh admin
    if (!$registration->is_attended) {
        return back()->with('error', 'Sertifikat belum tersedia karena Anda belum terdata hadir.');
    }

    // 3. Ambil data lengkap untuk sertifikat
    $data = [
        'nama' => $registration->user->name,
        'kegiatan' => $registration->event->title,
        'tanggal' => \Carbon\Carbon::parse($registration->event->event_date)->format('d F Y'),
        'nomor_sertifikat' => 'AAT/' . $registration->event->id . '/' . $registration->user->id . '/' . date('Y'),
        'sekre' => $registration->event->secretariat->name,
    ];

    // 4. Render view ke PDF
    $pdf = Pdf::loadView('emails.certificate_template', $data)->setPaper('a4', 'landscape');

    // 5. Download file
    return $pdf->download('Sertifikat_' . $registration->event->title . '.pdf');
}
// Menampilkan halaman Edit Kegiatan
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        // Keamanan: Pastikan admin hanya bisa edit kegiatan di sekrenya sendiri (kecuali Pusat)
        if (!auth()->user()->hasRole('Super Admin Pusat') && $event->secretariat_id != auth()->user()->secretariat_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit kegiatan ini.');
        }

        return view('admin.events.edit', compact('event'));
    }

    // Memproses Perubahan (Update)
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_date = $request->event_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->quota = $request->quota;

        // Jika admin mengunggah foto cover baru
        if ($request->hasFile('cover_image')) {
            // Hapus foto lama dari folder storage
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }
            // Simpan foto baru
            $event->cover_image = $request->file('cover_image')->store('event_covers', 'public');
        }

        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Data kegiatan berhasil diperbarui!');
    }

    // Memproses Hapus Kegiatan
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        // Hapus foto cover dari folder storage jika ada
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Kegiatan berhasil dihapus!');
    }
}