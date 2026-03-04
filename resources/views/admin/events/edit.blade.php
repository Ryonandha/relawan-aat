<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.events.index') }}" class="text-gray-500 hover:text-aat-blue text-2xl font-bold">←</a>
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Edit Kegiatan: ') }} <span class="text-aat-yellow">{{ $event->title }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue p-8">
                
                <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Judul Kegiatan</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border-gray-300 rounded p-2 focus:ring-aat-blue" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="4" class="w-full border-gray-300 rounded p-2 focus:ring-aat-blue">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Tanggal Pelaksanaan</label>
                            <input type="date" name="event_date" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d')) }}" class="w-full border-gray-300 rounded p-2 focus:ring-aat-blue" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Jam Mulai</label>
                            <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i')) }}" class="w-full border-gray-300 rounded p-2 focus:ring-aat-blue">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Jam Selesai</label>
                            <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i')) }}" class="w-full border-gray-300 rounded p-2 focus:ring-aat-blue">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Kuota Relawan</label>
                        <input type="number" name="quota" value="{{ old('quota', $event->quota) }}" class="w-full md:w-1/3 border-gray-300 rounded p-2 focus:ring-aat-blue" min="1" required>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <label class="block text-gray-700 font-bold mb-2">Ganti Poster / Cover Kegiatan</label>
                        
                        @if($event->cover_image)
                            <div class="mb-3">
                                <p class="text-sm text-gray-500 mb-1">Cover saat ini:</p>
                                <img src="{{ asset('storage/' . $event->cover_image) }}" alt="Cover" class="h-32 object-cover rounded shadow-sm">
                            </div>
                        @endif
                        
                        <input type="file" name="cover_image" accept="image/*" class="w-full border-gray-300 bg-white rounded p-2">
                        <p class="text-xs text-gray-500 mt-1">*Kosongkan jika tidak ingin mengganti foto. Maks 2MB.</p>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-4">
                        <a href="{{ route('admin.events.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded transition">Batal</a>
                        <button type="submit" class="bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-6 rounded transition">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>