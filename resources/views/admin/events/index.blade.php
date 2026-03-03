<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Manajemen Kegiatan') }}
            </h2>
            <a href="{{ route('admin.events.create') }}" class="bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-6 rounded-full shadow-md transition">
                + Buat Kegiatan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue p-6">
                <h3 class="text-xl font-extrabold text-gray-800 mb-6">Daftar Kegiatan Regional: <span class="text-aat-yellow">{{ auth()->user()->secretariat->name ?? 'Pusat' }}</span></h3>
                
                @if($events->isEmpty())
                    <div class="text-center py-10 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <p class="text-gray-500 font-medium">Belum ada kegiatan yang dibuat di regional ini.</p>
                        <a href="{{ route('admin.events.create') }}" class="text-aat-blue font-bold hover:underline mt-2 inline-block">Buat kegiatan pertama sekarang!</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                            <div class="border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-lg transition bg-white flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-aat-blue text-lg leading-tight">{{ $event->title }}</h4>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600 mb-3 bg-gray-100 inline-block px-2 py-1 rounded">
                                        📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}
                                    </p>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>
                                </div>
                                
                                <div class="border-t border-gray-100 pt-4 mt-auto">
                                    <div class="flex justify-between items-center mb-3 text-sm">
                                        <span class="text-gray-500">Pendaftar: <strong>{{ $event->registrations_count }} / {{ $event->quota }}</strong></span>
                                        <span class="text-gray-500">Jam: <strong>{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}</strong></span>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.events.participants', $event->id) }}" class="flex-1 text-center bg-aat-yellow hover:bg-yellow-500 text-aat-text py-2 rounded font-bold shadow-sm transition text-sm">
                                            👥 Lihat Peserta
                                        </a>
                                        <a href="#" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded font-bold shadow-sm transition text-sm">
                                            ✏️
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>