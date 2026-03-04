<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">Manajemen Regional (Sekretariat)</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 grid md:grid-cols-3 gap-6">
        <div class="bg-white p-6 shadow-xl sm:rounded-xl border-t-4 border-aat-yellow h-fit">
            <h3 class="font-bold text-lg mb-4 text-aat-blue">Tambah Regional Baru</h3>
            <form action="{{ route('admin.secretariats.store') }}" method="POST">
                @csrf
                <input type="text" name="name" class="w-full border-gray-300 rounded focus:ring-aat-blue mb-4" placeholder="Contoh: Malang" required>
                <button type="submit" class="w-full bg-aat-blue text-white font-bold py-2 rounded">Simpan Regional</button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white p-6 shadow-xl sm:rounded-xl border-t-4 border-aat-blue">
            <h3 class="font-bold text-lg mb-4 text-aat-blue">Daftar Regional Aktif</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Nama Regional</th>
                        <th class="p-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($secretariats as $sekre)
                    <tr>
                        <td class="p-3 border">{{ $sekre->id }}</td>
                        <td class="p-3 border font-bold">{{ $sekre->name }}</td>
                        <td class="p-3 border text-center">
                            <form action="{{ route('admin.secretariats.destroy', $sekre->id) }}" method="POST" onsubmit="return confirm('Yakin hapus regional ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 font-bold hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>