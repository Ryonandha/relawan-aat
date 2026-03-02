<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Daftar Kegiatan Relawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-aat-yellow">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-bold text-aat-blue">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-500 mb-2">📍 {{ $event->secretariat->name ?? 'Semua Sekre' }}</p>
                            <p class="text-sm text-gray-700 mb-4">{{ Str::limit($event->description, 100) }}</p>
                            
                            <div class="flex justify-between items-center text-sm mb-4">
                                <span>📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                                <span>⏰ {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}</span>
                            </div>

                            <p class="text-xs font-semibold text-gray-600 mb-4">Kuota: {{ $event->registrations()->count() }} / {{ $event->quota }}</p>

                            <form action="{{ route('events.join', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-4 rounded transition duration-150">
                                    Daftar Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>