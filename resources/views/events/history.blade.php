<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Riwayat Pendaftaran Kegiatan Saya') }}
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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-yellow p-6">
                @if($registrations->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-lg font-bold mb-4">Anda belum mendaftar kegiatan apapun.</p>
                        <a href="{{ route('events.index') }}" class="bg-aat-blue hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">Cari Kegiatan</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="p-4 border">Nama Kegiatan</th>
                                    <th class="p-4 border">Tanggal</th>
                                    <th class="p-4 border">Regional</th>
                                    <th class="p-4 border text-center">Status Kehadiran</th>
                                    <th class="p-4 border text-center">Sertifikat</th>
                                    <th class="p-4 border text-center border-l-2 border-l-red-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $reg)
                                <tr class="hover:bg-gray-50 transition border-b">
                                    <td class="p-4 font-bold text-aat-blue">{{ $reg->event->title ?? 'Kegiatan Telah Dihapus' }}</td>
                                    <td class="p-4 text-gray-600">{{ $reg->event ? \Carbon\Carbon::parse($reg->event->event_date)->format('d F Y') : '-' }}</td>
                                    <td class="p-4 text-gray-600">{{ $reg->event->secretariat->name ?? '-' }}</td>
                                    
                                    <td class="p-4 text-center">
                                        @if($reg->status === 'Registered')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold shadow-sm">Menunggu Hari H</span>
                                        @elseif($reg->status === 'Attended')
                                            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-bold shadow-sm">Hadir (Selesai)</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full font-bold">{{ $reg->status }}</span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-center">
                                        @if($reg->status === 'Attended')
                                            <a href="{{ route('certificate.download', $reg->id) }}" class="text-sm bg-aat-blue hover:bg-blue-800 text-white px-4 py-2 rounded-lg font-bold shadow-sm inline-block transition">Download</a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tersedia setelah hadir</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-center border-l-2 border-l-red-100">
                                        @if($reg->event && $reg->status === 'Registered' && \Carbon\Carbon::parse($reg->event->event_date)->startOfDay()->gte(now()->startOfDay()))
                                            <form action="{{ route('events.cancel', $reg->id) }}" method="POST" onsubmit="return confirm('Sakit atau berhalangan hadir? Apakah Anda yakin ingin MEMBATALKAN pendaftaran ini? Kuota Anda akan dikosongkan dan diberikan kepada relawan lain.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm bg-red-50 hover:bg-red-600 hover:text-white border border-red-300 text-red-600 px-3 py-1.5 rounded-lg font-bold shadow-sm transition">X Batal Daftar</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 font-bold">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>