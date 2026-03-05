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
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-gray-600">Total Pendaftar: <strong>{{ $registrations->count() }} / {{ $event->quota }}</strong></p>
                    <a href="{{ route('admin.events.index') }}" class="text-sm font-bold text-aat-blue hover:underline">← Kembali ke Daftar Kegiatan</a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aat-gray text-aat-blue">
                            <th class="p-3 border">Nama Relawan</th>
                            <th class="p-3 border text-center">ID SIANAS</th>
                            <th class="p-3 border text-center">Status Kehadiran</th>
                            <th class="p-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border font-bold text-gray-700">{{ $reg->user->name }}</td>
                            <td class="p-3 border text-center font-mono text-sm text-gray-500">{{ $reg->user->sianas_id ?? '-' }}</td>
                            <td class="p-3 border text-center">
                                @if($reg->status === 'Attended')
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">HADIR</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">TERDAFTAR</span>
                                @endif
                            </td>
                            <td class="p-3 border text-center">
                                <form action="{{ route('admin.events.checkin', $reg->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="{{ $reg->status === 'Attended' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-aat-blue hover:bg-blue-800 text-white' }} font-bold text-xs px-4 py-2 rounded-lg shadow-sm transition">
                                        {{ $reg->status === 'Attended' ? 'X Batalkan Hadir' : '✓ Tandai Hadir' }}
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