<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">Buat Kegiatan Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border-t-4 border-aat-blue">
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama Kegiatan</label>
                        <input type="text" name="title" class="w-full rounded border-gray-300" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700">Tanggal</label>
                            <input type="date" name="event_date" class="w-full rounded border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Kuota Relawan</label>
                            <input type="number" name="quota" class="w-full rounded border-gray-300" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Deskripsi</label>
                        <textarea name="description" class="w-full rounded border-gray-300"></textarea>
                    </div>
                    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Poster / Cover Kegiatan (Opsional)</label>
        <input type="file" name="cover_image" accept="image/*" class="w-full border-gray-300 rounded p-2">
    </div>
                    <button type="submit" class="bg-aat-blue text-white px-6 py-2 rounded font-bold hover:bg-aat-blue-light">
                        Simpan Kegiatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>