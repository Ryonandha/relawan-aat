<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Riwayat Kegiatan & Sertifikat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-aat-yellow">
                
                @if($myRegistrations->isEmpty())
                    <p class="text-center text-gray-500 py-8">Kamu belum mendaftar di kegiatan apa pun. <a href="{{ route('events.index') }}" class="text-aat-blue underline">Cari kegiatan sekarang!</a></p>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-aat-gray text-aat-blue">
                                <th class="p-3 border">Kegiatan</th>
                                <th class="p-3 border">Regional</th>
                                <th class="p-3 border text-center">Status Presensi</th>
                                <th class="p-3 border text-center">Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myRegistrations as $reg)
                            <tr>
                                <td class="p-3 border font-bold">{{ $reg->event->title }}</td>
                                <td class="p-3 border text-sm text-gray-600">{{ $reg->event->secretariat->name }}</td>
                                <td class="p-3 border text-center">
                                    @if($reg->is_attended)
                                        <span class="text-green-600 font-bold">✓ Hadir</span>
                                    @else
                                        <span class="text-red-500 italic">Belum Presensi</span>
                                    @endif
                                </td>
                                <td class="p-3 border text-center">
                                @if($reg->is_attended)
                                    <a href="{{ route('certificate.download', $reg->id) }}" class="bg-aat-blue hover:bg-aat-blue-light text-white text-xs font-bold py-2 px-4 rounded transition shadow">
                                        📥 Unduh PDF
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic">Tersedia setelah hadir</span>
                                @endif
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>