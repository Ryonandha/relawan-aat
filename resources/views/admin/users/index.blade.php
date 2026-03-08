<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Data Pengguna: ') }} <span class="text-aat-yellow">{{ $namaRegional ?? 'Wilayah' }}</span>
            </h2>
            @if(auth()->user()->hasRole('Super Admin Pusat'))
            <a href="{{ route('admin.users.create') }}" class="bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-6 rounded-full shadow-md transition">
                + Tambah Admin Sekre
            </a>
            @endif
        </div>
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

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500 font-medium">Gunakan kotak di samping untuk mencari relawan berdasarkan Nama, Email, atau ID SIANAS.</p>
                <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-1/3 flex">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pengguna..." class="w-full border-gray-300 rounded-l-lg focus:ring-aat-blue focus:border-aat-blue text-sm">
                    <button type="submit" class="bg-aat-blue text-white px-4 rounded-r-lg font-bold hover:bg-blue-800 transition">Cari</button>
                    @if(isset($search) && $search != '')
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 rounded-r-lg font-bold flex items-center ml-1 hover:bg-gray-300 transition" title="Reset Pencarian">X</a>
                    @endif
                </form>
            </div>

            @if(auth()->user()->hasRole('Super Admin Pusat'))
            <div class="mb-8">
                <h3 class="text-lg font-extrabold text-aat-blue mb-4 flex items-center gap-2">🛡️ Daftar Pengurus & Admin</h3>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-yellow p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-aat-gray text-aat-blue">
                                    <th class="p-3 border">Nama Lengkap</th>
                                    <th class="p-3 border">Email</th>
                                    <th class="p-3 border text-center">Regional</th>
                                    <th class="p-3 border text-center">Peran</th>
                                    <th class="p-3 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                <tr class="hover:bg-gray-50 transition border-b">
                                    <td class="p-3 border font-semibold text-gray-800">{{ $admin->name }}</td>
                                    <td class="p-3 border text-gray-600">{{ $admin->email }}</td>
                                    <td class="p-3 border text-center">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold">{{ $admin->secretariat->name ?? 'Pusat' }}</span>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">{{ collect($admin->roles)->pluck('name')->first() }}</span>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.users.edit', $admin->id) }}" class="text-sm bg-aat-yellow hover:bg-yellow-500 text-black px-3 py-1.5 rounded font-bold shadow-sm">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ADMIN ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded font-bold shadow-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500 font-bold">Admin tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $admins->links() }}
                    </div>

                </div>
            </div>
            @endif

            <div>
                <h3 class="text-lg font-extrabold text-aat-blue mb-4 flex items-center gap-2">🧑‍🤝‍🧑 Daftar Relawan</h3>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue p-6">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-aat-gray text-aat-blue">
                                    <th class="p-3 border">Nama Lengkap</th>
                                    <th class="p-3 border">Email / Kontak</th>
                                    <th class="p-3 border text-center">ID SIANAS</th>
                                    <th class="p-3 border text-center">Regional</th>
                                    <th class="p-3 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($relawans as $relawan)
                                <tr class="hover:bg-gray-50 transition border-b">
                                    <td class="p-3 border font-semibold text-gray-800">{{ $relawan->name }}</td>
                                    <td class="p-3 border text-gray-600">
                                        {{ $relawan->email }}<br>
                                        <span class="text-xs text-green-600 font-bold flex items-center gap-1 mt-1">📞 {{ $relawan->phone_number ?? '-' }}</span>
                                    </td>
                                    <td class="p-3 border text-center font-mono text-sm text-gray-600">{{ $relawan->sianas_id ?? 'Belum Ada' }}</td>
                                    <td class="p-3 border text-center">
                                        <span class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full font-bold">{{ $relawan->secretariat->name ?? '-' }}</span>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.users.edit', $relawan->id) }}" class="text-sm bg-aat-yellow hover:bg-yellow-500 text-black px-3 py-1.5 rounded font-bold shadow-sm">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $relawan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus RELAWAN ini? Data pendaftaran kegiatannya juga akan ikut terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded font-bold shadow-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500 font-bold">
                                        @if(isset($search) && $search != '')
                                            Tidak ada relawan yang cocok dengan pencarian "{{ $search }}".
                                        @else
                                            Belum ada relawan yang terdaftar di regional ini.
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $relawans->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>