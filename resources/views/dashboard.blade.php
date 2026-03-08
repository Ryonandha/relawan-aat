<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue mb-6">
                <div class="p-6 sm:p-8 flex items-center gap-4">
                    <div class="bg-blue-100 p-4 rounded-full text-2xl">
                        👤
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-gray-600 mt-1">Selamat datang kembali di Portal Relawan Anak-Anak Terang.</p>
                    </div>
                </div>
            </div>

            @hasanyrole('Super Admin Pusat|Admin Sekre')
                <div class="mb-4 mt-8">
                    <h4 class="text-lg font-bold text-gray-700">📊 Statistik {{ $labelStat ?? '' }}</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total Relawan</p>
                        <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ $totalRelawan ?? 0 }} <span class="text-sm font-medium text-gray-500">orang</span></p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total Kegiatan</p>
                        <p class="text-4xl font-extrabold text-yellow-600 mt-2">{{ $totalKegiatan ?? 0 }} <span class="text-sm font-medium text-gray-500">acara</span></p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 hover:shadow-md transition">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Regional Aktif</p>
                        <p class="text-4xl font-extrabold text-green-600 mt-2">{{ $totalSekre ?? 0 }} <span class="text-sm font-medium text-gray-500">lokasi</span></p>
                    </div>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl shadow-sm border border-blue-100 text-blue-800">
                    <p class="font-medium">💡 <strong>Tips Cepat:</strong> Gunakan menu di navigasi atas untuk mengelola data kegiatan, melihat pendaftar, dan memanajemen pengguna/relawan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 mt-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-blue-800 uppercase tracking-wide mb-1">Riwayat Partisipasi</p>
                                <p class="text-5xl font-extrabold text-blue-600">{{ $totalDiikuti ?? 0 }}</p>
                                <p class="text-sm text-blue-700 mt-2">Total kegiatan didaftar / diikuti</p>
                            </div>
                            <div class="text-6xl opacity-40 drop-shadow-md">🤝</div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-sm border border-yellow-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-yellow-800 uppercase tracking-wide mb-1">Sertifikat Terkumpul</p>
                                <p class="text-5xl font-extrabold text-yellow-600">{{ $totalSertifikat ?? 0 }}</p>
                                <p class="text-sm text-yellow-700 mt-2">Tersedia untuk diunduh (Check-in)</p>
                            </div>
                            <div class="text-6xl opacity-40 drop-shadow-md">🎓</div>
                        </div>
                    </div>
                </div>

                <h4 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">📅 Pengingat: Jadwal Kegiatan Anda Mendatang</h4>
                
                @if(isset($kegiatanMendatang) && $kegiatanMendatang->isEmpty())
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center">
                        <p class="text-gray-500 font-medium mb-4">Anda belum memiliki jadwal kegiatan terdekat saat ini.</p>
                        <a href="{{ route('events.index') }}" class="bg-aat-blue hover:bg-blue-800 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-md inline-block">Cari Kegiatan Seru!</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($kegiatanMendatang as $reg)
                            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-aat-yellow hover:shadow-md transition flex flex-col h-full">
                                <h5 class="font-bold text-aat-blue mb-2 leading-tight" title="{{ $reg->event->title }}">{{ $reg->event->title }}</h5>
                                <p class="text-sm text-gray-600 mb-1">🗓️ {{ \Carbon\Carbon::parse($reg->event->event_date)->translatedFormat('d F Y') }}</p>
                                <p class="text-sm text-gray-600 mb-3">⏰ {{ \Carbon\Carbon::parse($reg->event->start_time)->format('H:i') }} - Selesai</p>
                                <div class="mt-auto pt-3 border-t border-gray-100">
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold">⏳ Menunggu Hari H</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('events.history') }}" class="text-sm text-aat-blue font-bold hover:underline bg-gray-100 px-4 py-2 rounded-lg inline-block">Lihat Selengkapnya di Riwayat →</a>
                    </div>
                @endif
            @endhasanyrole

        </div>
    </div>
</x-app-layout>