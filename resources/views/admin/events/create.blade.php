<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">Buat Kegiatan Baru</h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-xl sm:rounded-xl border-t-4 border-aat-blue">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-1">Nama Kegiatan</label>
                    <input type="text" name="title" class="w-full border-gray-300 rounded focus:ring-aat-blue" required>
                </div>
                
                <div class="mb-4">
                    <label class="block font-bold mb-1">Deskripsi Kegiatan</label>
                    <textarea name="description" rows="4" class="w-full border-gray-300 rounded focus:ring-aat-blue" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block font-bold mb-1">Tanggal Kegiatan</label>
                        <input type="date" name="event_date" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" class="w-full border-gray-300 rounded" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-bold mb-1">Lokasi</label>
                        <input type="text" name="location" class="w-full border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Kuota Relawan</label>
                        <input type="number" name="quota" min="1" class="w-full border-gray-300 rounded" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-1">Regional (Sekretariat)</label>
                    <select name="secretariat_id" class="w-full border-gray-300 rounded" required>
                        @foreach($secretariats as $sekre)
                            <option value="{{ $sekre->id }}" {{ (auth()->user()->secretariat_id == $sekre->id) ? 'selected' : '' }}>
                                {{ $sekre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-1">Upload Poster/Cover (Opsional)</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full border-gray-300 p-2 border rounded">
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.events.index') }}" class="text-gray-500 font-bold mt-2">Batal</a>
                    <button type="submit" class="bg-aat-blue hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg">Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>