<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relawan - Yayasan Anak-Anak Terang</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-aat-gray text-gray-800 font-sans selection:bg-aat-yellow selection:text-aat-blue">

    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <img src="{{ asset('storage/logo/logo-AAT.png') }}" alt="Logo AAT" class="h-10 w-auto">
<span class="font-bold text-xl text-aat-blue tracking-tight">Relawan<span class="text-aat-yellow">AAT</span></span>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-semibold text-aat-blue hover:text-aat-blue-light">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-aat-blue">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-aat-blue hover:bg-aat-blue-light text-white px-5 py-2 rounded-full font-bold transition shadow-md">Daftar Relawan</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative bg-aat-blue pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6">
                Mari Berbagi Cahaya <br class="hidden md:block"> Bersama <span class="text-aat-yellow">Anak-Anak Terang</span>
            </h1>
            <p class="mt-4 text-xl text-blue-200 max-w-3xl mx-auto mb-10">
                Bergabunglah menjadi relawan dan bantu anak-anak serta siswa dari keluarga kurang mampu untuk mendapatkan akses pendidikan yang layak dan berkelanjutan.
            </p>
            <div class="flex justify-center gap-4">
                <a href="#kegiatan" class="bg-aat-yellow hover:bg-yellow-500 text-aat-text px-8 py-3 rounded-full font-bold text-lg transition shadow-lg">
                    Lihat Kegiatan
                </a>
            </div>
        </div>
    </div>

    <div id="kegiatan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-aat-blue">Kegiatan Terdekat</h2>
                <div class="w-24 h-1 bg-aat-yellow mx-auto mt-4 rounded"></div>
                <p class="mt-4 text-gray-600">Jadwal pendampingan dan acara regional yang membutuhkan bantuanmu.</p>
            </div>

            @if($latestEvents->isEmpty())
                <div class="text-center py-12 bg-aat-gray rounded-lg border-2 border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">Belum ada kegiatan terdekat yang dijadwalkan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestEvents as $event)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col">
                            
                            @if($event->cover_image)
                                <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="h-48 bg-aat-blue flex flex-col items-center justify-center text-aat-yellow opacity-90 relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                                    <span class="font-extrabold text-4xl relative z-10">AAT</span>
                                    <span class="text-white text-sm mt-2 relative z-10">Relawan Mengajar</span>
                                </div>
                            @endif
                            <div class="h-1 bg-aat-yellow w-full"></div>

                            <div class="p-6 flex-grow">
                                <div class="text-sm text-aat-blue font-semibold mb-2 flex items-center gap-2">
                                    📍 {{ $event->secretariat->name ?? 'Pusat' }}
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $event->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $event->description }}</p>
                                
                                <div class="flex justify-between items-center text-sm font-medium text-gray-500 border-t pt-4">
                                    <span>📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                                    <span>⏰ {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}</span>
                                </div>
                            </div>
                            
                            <div class="bg-aat-gray px-6 py-4 mt-auto">
                                <a href="{{ route('login') }}" class="block text-center w-full bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-4 rounded transition">
                                    Ikut Serta
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <footer class="bg-aat-blue text-white py-12 border-t border-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div>
                <h3 class="text-2xl font-bold text-aat-yellow mb-4">AAT Indonesia</h3>
                <p class="text-blue-200 text-sm">Sistem Informasi Manajemen Relawan terintegrasi untuk mendukung misi Yayasan Anak-Anak Terang.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Tautan Penting</h4>
                <ul class="space-y-2 text-blue-200 text-sm">
                    <li><a href="https://aat.or.id" target="_blank" class="hover:text-aat-yellow transition">Website Resmi AAT</a></li>
                    <li><a href="https://sianas.aat.or.id" target="_blank" class="hover:text-aat-yellow transition">Portal SIANAS</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Sekretariat</h4>
                <p class="text-blue-200 text-sm">Purwokerto, Bandung, Madiun, Semarang, Jogja, dan Malang.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-blue-800 text-center text-sm text-blue-300">
            &copy; {{ date('Y') }} Yayasan Anak-Anak Terang Indonesia. All rights reserved.
        </div>
    </footer>
</body>
</html>