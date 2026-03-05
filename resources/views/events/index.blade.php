<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Daftar Kegiatan Tersedia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6 font-bold">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6 font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue flex flex-col justify-between">
                        @if($event->cover_image)
                            <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 font-bold">Tidak ada gambar cover</div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="font-bold text-xl text-aat-blue mb-2">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600 mb-1">📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}</p>
                            <p class="text-sm text-gray-600 mb-1">📍 {{ $event->location }}</p>
                            <p class="text-sm text-gray-600 mb-4">🏠 Regional: <span class="font-bold">{{ $event->secretariat->name ?? '-' }}</span></p>
                            <p class="text-sm text-gray-700 line-clamp-3 mb-4">{{ $event->description }}</p>
                            
                            <div class="mb-4">
                                <p class="text-sm font-bold text-gray-700 border-b pb-2">Sisa Kuota Pendaftar: 
                                    @if($event->sisa_kuota > 0)
                                        <span class="text-green-600">{{ $event->sisa_kuota }} orang lagi</span>
                                    @else
                                        <span class="text-red-600">Penuh (0)</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0 mt-auto">
                            @if(!auth()->check())
                                <a href="{{ route('login') }}" class="block text-center w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2.5 px-4 rounded-lg transition">Login untuk Mendaftar</a>
                            @elseif($event->is_joined)
                                <button disabled class="block w-full bg-green-100 text-green-800 border border-green-400 font-bold py-2.5 px-4 rounded-lg cursor-not-allowed text-center">✅ Sudah Terdaftar</button>
                            @elseif($event->is_penuh)
                                <button disabled class="block w-full bg-red-100 text-red-700 border border-red-400 font-bold py-2.5 px-4 rounded-lg cursor-not-allowed text-center">🚫 Kuota Habis</button>
                            @else
                                <form action="{{ route('events.join', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengamankan kuota dan mengikuti kegiatan ini?');" class="w-full bg-aat-blue hover:bg-blue-800 text-white font-bold py-2.5 px-4 rounded-lg shadow-md transition">Daftar & Ikut Serta</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-8 rounded-xl shadow-sm text-center border-t-4 border-gray-300">
                        <p class="text-gray-500 font-bold text-lg">Belum ada kegiatan terdekat saat ini.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>