<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Manajemen Pengguna (Admin)') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-6 rounded-full shadow-md transition">
                + Tambah Admin Sekre
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-aat-gray text-aat-blue">
                            <th class="p-3 border">Nama Lengkap</th>
                            <th class="p-3 border">Email</th>
                            <th class="p-3 border text-center">Regional</th>
                            <th class="p-3 border text-center">Peran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border font-semibold text-gray-800">{{ $admin->name }}</td>
                            <td class="p-3 border text-gray-600">{{ $admin->email }}</td>
                            <td class="p-3 border text-center">
                                @if($admin->secretariat)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold">{{ $admin->secretariat->name }}</span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Pusat / Super Admin</span>
                                @endif
                            </td>
                            <td class="p-3 border text-center">
                                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">{{ $admin->roles->pluck('name')->first() }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>