<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            Peserta: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-aat-blue">
                <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-gray-600">Total Pendaftar: <span class="font-extrabold text-lg text-aat-blue">{{ $registrations->count() }} / {{ $event->quota }}</span></p>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('admin.events.export', $event->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center gap-2 text-sm">
                            📥 Export Excel / CSV
                        </a>
                        
                        <a href="{{ route('admin.events.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg transition text-sm">
                            ← Kembali
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-aat-gray text-aat-blue">
                                <th class="p-3 border">Nama Relawan</th>
                                <th class="p-3 border text-center">ID SIANAS</th>
                                <th class="p-3 border text-center">Nomor WA</th>
                                <th class="p-3 border text-center">Status Kehadiran</th>
                                <th class="p-3 border text-center">Aksi Check-In</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                            <tr class="hover:bg-gray-50 transition border-b">
                                <td class="p-3 border font-bold text-gray-700">{{ $reg->user->name }}</td>
                                <td class="p-3 border text-center font-mono text-sm text-gray-500">{{ $reg->user->sianas_id ?? '-' }}</td>
                                <td class="p-3 border text-center text-sm text-green-600 font-bold">{{ $reg->user->phone_number ?? '-' }}</td>
                                <td class="p-3 border text-center">
                                    @if($reg->status === 'Attended')
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm border border-green-200">HADIR</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm border border-yellow-200">TERDAFTAR</span>
                                    @endif
                                </td>
                                <td class="p-3 border text-center">
                                    <form action="{{ route('admin.events.checkin', $reg->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="{{ $reg->status === 'Attended' ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white' : 'bg-aat-blue hover:bg-blue-800 text-white' }} font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition">
                                            {{ $reg->status === 'Attended' ? 'X Batalkan Hadir' : '✓ Tandai Hadir' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500 font-bold">Belum ada relawan yang mendaftar kegiatan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>