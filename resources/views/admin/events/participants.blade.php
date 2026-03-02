<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            Peserta: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-aat-blue">
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-gray-600">Total Pendaftar: <strong>{{ $participants->count() }} / {{ $event->quota }}</strong></p>
                    <a href="{{ route('admin.events.index') }}" class="text-sm text-aat-blue hover:underline">← Kembali ke Daftar Kegiatan</a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aat-gray text-aat-blue">
                            <th class="p-3 border">Nama Relawan</th>
                            <th class="p-3 border">ID SIANAS</th>
                            <th class="p-3 border text-center">Status Kehadiran</th>
                            <th class="p-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $reg)
                        <tr>
                            <td class="p-3 border">{{ $reg->user->name }}</td>
                            <td class="p-3 border font-mono text-sm text-gray-500">{{ $reg->user->sianas_id ?? '-' }}</td>
                            <td class="p-3 border text-center">
                                @if($reg->is_attended)
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">HADIR</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded">ALFA</span>
                                @endif
                            </td>
                            <td class="p-3 border text-center">
                                <form action="{{ route('admin.events.checkin', $reg->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="{{ $reg->is_attended ? 'bg-gray-500' : 'bg-aat-blue' }} text-white text-xs px-3 py-1 rounded hover:opacity-80">
                                        {{ $reg->is_attended ? 'Batalkan Hadir' : 'Tandai Hadir' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>