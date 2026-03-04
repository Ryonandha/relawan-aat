<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Data Pengguna: ') }} <span class="text-aat-yellow">{{ $namaRegional }}</span>
            </h2>
            @role('Super Admin Pusat')
            <a href="{{ route('admin.users.create') }}" class="bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-6 rounded-full shadow-md transition">
                + Tambah Admin Sekre
            </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @role('Super Admin Pusat')
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
                                @foreach($admins as $admin)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 border font-semibold text-gray-800">{{ $admin->name }}</td>
                                    <td class="p-3 border text-gray-600">{{ $admin->email }}</td>
                                    <td class="p-3 border text-center">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold">{{ $admin->secretariat->name ?? 'Pusat' }}</span>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">{{ $admin->roles->pluck('name')->first() }}</span>
                                    </td>
                                    <td class="p-3 border text-center">
                                        <a href="{{ route('admin.users.edit', $admin->id) }}" class="text-sm bg-aat-yellow hover:bg-yellow-500 text-black px-3 py-1 rounded font-bold shadow-sm">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endrole

            <div>
                <h3 class="text-lg font-extrabold text-aat-blue mb-4 flex items-center gap-2">🧑‍🤝‍🧑 Daftar Relawan</h3>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue p-6">
                    
                    @if($relawans->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-lg font-bold">Belum ada relawan yang terdaftar di regional ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-aat-gray text-aat-blue">
                                        <th class="p-3 border">Nama Lengkap</th>
                                        <th class="p-3 border">Email / Kontak</th>
                                        <th class="p-3 border text-center">ID SIANAS</th>
                                        <th class="p-3 border text-center">Regional</th>
                                        @role('Super Admin Pusat')
                                        <th class="p-3 border text-center">Aksi</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($relawans as $relawan)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 border font-semibold text-gray-800">{{ $relawan->name }}</td>
                                        <td class="p-3 border text-gray-600">
                                            {{ $relawan->email }}<br>
                                            <span class="text-xs text-green-600 font-bold flex items-center gap-1 mt-1">📞 {{ $relawan->phone_number ?? '-' }}</span>
                                        </td>
                                        <td class="p-3 border text-center font-mono text-sm text-gray-600">{{ $relawan->sianas_id ?? 'Belum Ada' }}</td>
                                        <td class="p-3 border text-center">
                                            <span class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full font-bold">{{ $relawan->secretariat->name ?? '-' }}</span>
                                        </td>
                                        @role('Super Admin Pusat')
                                        <td class="p-3 border text-center">
                                            <a href="{{ route('admin.users.edit', $relawan->id) }}" class="text-sm bg-aat-yellow hover:bg-yellow-500 text-black px-3 py-1 rounded font-bold shadow-sm">Edit</a>
                                        </td>
                                        @endrole
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    
                </div>
            </div>

        </div>
    </div>
</x-app-layout>