<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-aat-blue">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-aat-blue mb-2">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h3>
                    
                    @role('Super Admin Pusat')
                        <p class="mb-4 text-gray-600">Anda berada di panel Super Admin Pusat. Di sini Anda memiliki kendali penuh atas semua data regional Yayasan AAT.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-aat-gray p-4 rounded-lg shadow border-l-4 border-aat-yellow">
                                <h4 class="font-bold text-lg">Total Relawan</h4>
                                <p class="text-3xl font-extrabold text-aat-blue">--</p>
                            </div>
                            <div class="bg-aat-gray p-4 rounded-lg shadow border-l-4 border-aat-yellow">
                                <h4 class="font-bold text-lg">Total Sekre</h4>
                                <p class="text-3xl font-extrabold text-aat-blue">--</p>
                            </div>
                        </div>
                    @endrole

                    @role('Admin Sekre')
                        <p class="mb-4 text-gray-600">Anda mengelola kegiatan dan relawan khusus untuk regional: <span class="font-bold text-aat-blue">{{ Auth::user()->secretariat->name ?? 'Belum ada sekre' }}</span>.</p>
                        <div class="mt-4">
                            <a href="#" class="inline-block bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-4 rounded transition">
                                + Buat Kegiatan Baru
                            </a>
                        </div>
                    @endrole

                    @role('Relawan')
                        <p class="mb-4 text-gray-600">Terima kasih atas dedikasimu menjadi bagian dari Anak-Anak Terang!</p>
                        <div class="flex gap-4 mt-4">
                            <a href="{{ route('events.index') }}" class="bg-aat-blue hover:bg-aat-blue-light text-white font-bold py-2 px-4 rounded transition">
                                🔍 Cari Kegiatan
                            </a>
                            <a href="#" class="bg-aat-yellow hover:bg-yellow-500 text-aat-text font-bold py-2 px-4 rounded shadow transition">
                                📜 Sertifikat & Portofolio
                            </a>
                        </div>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>