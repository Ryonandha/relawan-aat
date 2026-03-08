<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Secretariat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    // --- SISI ADMIN ---

    public function adminIndex(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Tangkap kata kunci pencarian dari URL
        $search = $request->input('search');

        if ($user->hasRole('Super Admin Pusat')) {
            // Super Admin: Lihat semua event dengan fitur search dan paginasi
            $events = Event::with('secretariat')
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%")
                                 ->orWhere('location', 'like', "%{$search}%");
                })
                ->orderBy('event_date', 'desc')
                ->paginate(9) // Tampilkan 9 kotak per halaman agar pas dengan grid 3 kolom
                ->appends(['search' => $search]);
                
            $namaRegional = 'Semua Wilayah (Nasional)';
        } else {
            // Admin Sekre: Hanya lihat event regionalnya dengan fitur search dan paginasi
            $events = Event::with('secretariat')
                ->where('secretariat_id', $user->secretariat_id)
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                          ->orWhere('location', 'like', "%{$search}%");
                    });
                })
                ->orderBy('event_date', 'desc')
                ->paginate(9)
                ->appends(['search' => $search]);
                
            $namaRegional = $user->secretariat->name ?? 'Wilayah';
        }

        return view('admin.events.index', compact('events', 'namaRegional', 'search'));
    }

    public function create()
    {
        $secretariats = Secretariat::all();
        return view('admin.events.create', compact('secretariats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required', // <- Wajib Diisi
            'end_time' => 'required',   // <- Wajib Diisi
            'location' => 'required|string|max:255',
            'quota' => 'required|integer|min:1',
            'secretariat_id' => 'required|exists:secretariats,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('event_covers', 'public');
        }

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time, // <- Simpan ke DB
            'end_time' => $request->end_time,     // <- Simpan ke DB
            'location' => $request->location,
            'quota' => $request->quota,
            'secretariat_id' => $request->secretariat_id,
            'cover_image' => $coverPath,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Kegiatan berhasil dibuat!');
    }

    public function edit(Event $event)
    {
        if (auth()->user()->hasRole('Admin Sekre') && $event->secretariat_id !== auth()->user()->secretariat_id) {
            abort(403, 'Akses Ditolak.');
        }
        $secretariats = Secretariat::all();
        return view('admin.events.edit', compact('event', 'secretariats'));
    }

    public function update(Request $request, Event $event)
    {
        if (auth()->user()->hasRole('Admin Sekre') && $event->secretariat_id !== auth()->user()->secretariat_id) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'quota' => 'required|integer|min:1',
            'secretariat_id' => 'required|exists:secretariats,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image && file_exists(storage_path('app/public/' . $event->cover_image))) {
                unlink(storage_path('app/public/' . $event->cover_image));
            }
            $data['cover_image'] = $request->file('cover_image')->store('event_covers', 'public');
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        if (auth()->user()->hasRole('Admin Sekre') && $event->secretariat_id !== auth()->user()->secretariat_id) {
            abort(403, 'Akses Ditolak.');
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Kegiatan berhasil dihapus!');
    }

    public function participants(Event $event)
    {
        if (auth()->user()->hasRole('Admin Sekre') && $event->secretariat_id !== auth()->user()->secretariat_id) {
            abort(403, 'Akses Ditolak.');
        }
        $registrations = EventRegistration::with('user')->where('event_id', $event->id)->get();
        return view('admin.events.participants', compact('event', 'registrations'));
    }

    public function checkIn(EventRegistration $registration)
    {
        if (auth()->user()->hasRole('Admin Sekre') && $registration->event->secretariat_id !== auth()->user()->secretariat_id) {
            abort(403, 'Akses Ditolak.');
        }
        
        // Logika Toggle (Saklar)
        if ($registration->status === 'Attended') {
            $registration->update(['status' => 'Registered']);
            return redirect()->back()->with('success', 'Status kehadiran berhasil DIBATALKAN.');
        } else {
            $registration->update(['status' => 'Attended']);
            return redirect()->back()->with('success', 'Relawan berhasil ditandai HADIR!');
        }
    }


    // --- SISI RELAWAN ---

    public function index()
    {
        $events = Event::with('secretariat')
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        foreach ($events as $event) {
            $terdaftar = EventRegistration::where('event_id', $event->id)->count();
            $event->sisa_kuota = max(0, $event->quota - $terdaftar);
            $event->is_penuh = $event->sisa_kuota === 0;
            
            if (auth()->check()) {
                $event->is_joined = EventRegistration::where('event_id', $event->id)
                                    ->where('user_id', auth()->id())
                                    ->exists();
            } else {
                $event->is_joined = false;
            }
        }
        return view('events.index', compact('events'));
    }

    public function join(Event $event)
    {
        $user = auth()->user();

        if (Carbon::parse($event->event_date)->isPast()) {
            return redirect()->back()->with('error', 'Maaf, kegiatan ini sudah berlalu.');
        }

        $jumlahPendaftar = EventRegistration::where('event_id', $event->id)->count();
        if ($jumlahPendaftar >= $event->quota) {
            return redirect()->back()->with('error', 'Maaf, pendaftaran gagal. Kuota kegiatan ini sudah penuh!');
        }

        $sudahDaftar = EventRegistration::where('event_id', $event->id)
                                        ->where('user_id', $user->id)
                                        ->exists();

        if ($sudahDaftar) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kegiatan ini.');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'Registered',
        ]);

        return redirect()->route('events.history')->with('success', 'Berhasil mendaftar kegiatan! Jangan lupa hadir ya.');
    }

    public function history()
    {
        $user = auth()->user();
        
        // Ambil data pendaftaran dan urutkan menggunakan Collection sortByDesc
        $registrations = EventRegistration::with('event.secretariat')
                            ->where('user_id', $user->id)
                            ->get()
                            ->sortByDesc(function ($registration) {
                                return $registration->event->event_date ?? now();
                            });

        return view('events.history', compact('registrations'));
    }

    public function cancel(EventRegistration $registration)
    {
        $user = auth()->user();
        if ($registration->user_id !== $user->id) abort(403, 'Akses ditolak.');
        if ($registration->status !== 'Registered') return redirect()->back()->with('error', 'Anda tidak dapat membatalkan kegiatan yang sudah selesai.');
        if (Carbon::parse($registration->event->event_date)->isPast()) return redirect()->back()->with('error', 'Tidak dapat membatalkan kegiatan yang lewat.');

        $registration->delete();
        return redirect()->route('events.history')->with('success', 'Pendaftaran dibatalkan. Kuota telah dikembalikan.');
    }

    public function downloadCertificate(EventRegistration $registration)
    {
        if (auth()->id() !== $registration->user_id) abort(403);
        if ($registration->status !== 'Attended') abort(403, 'Sertifikat belum tersedia.');

        $pdf = Pdf::loadView('emails.certificate_template', ['registration' => $registration])
                  ->setPaper('a4', 'landscape');
        return $pdf->download('Sertifikat_AAT_'.$registration->event->title.'.pdf');
    }
    public function exportParticipants(Event $event)
    {
        // Proteksi Akses
        if (auth()->user()->hasRole('Admin Sekre') && $event->secretariat_id !== auth()->user()->secretariat_id) {
            abort(403, 'Akses Ditolak.');
        }

        // Ambil data pendaftar
        $registrations = EventRegistration::with('user')->where('event_id', $event->id)->get();

        // Buat nama file rapi (Contoh: Absensi-Bakti-Sosial.csv)
        $fileName = 'Absensi-' . \Illuminate\Support\Str::slug($event->title) . '.csv';

        // Fitur Stream Download bawaan Laravel
        return response()->streamDownload(function () use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // 1. Buat Baris Judul Kolom (Header)
            fputcsv($file, ['No', 'Nama Relawan', 'ID SIANAS', 'Nomor WhatsApp', 'Email', 'Status Kehadiran']);

            // 2. Isi Data Baris per Baris
            $no = 1;
            foreach ($registrations as $reg) {
                $status = $reg->status === 'Attended' ? 'HADIR (Check-In)' : 'TERDAFTAR (Belum Hadir)';
                fputcsv($file, [
                    $no++,
                    $reg->user->name,
                    $reg->user->sianas_id ?? 'Belum Ada',
                    // Tambahkan spasi di depan nomor HP agar angka "0" di awal tidak hilang saat dibuka di Excel
                    " " . ($reg->user->phone_number ?? '-'),
                    $reg->user->email,
                    $status
                ]);
            }
            fclose($file);
        }, $fileName);
    }
}