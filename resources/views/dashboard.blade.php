<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-aat-blue leading-tight">
            {{ __('Dashboard Relawan AAT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-aat-yellow p-6">
                <div class="text-gray-900 font-bold text-xl mb-4">
                    Selamat datang, {{ Auth::user()->name }}! 👋
                </div>
                <p class="text-gray-600 mb-8">
                    Anda masuk sebagai: <span class="bg-aat-blue text-white px-2 py-1 rounded text-sm">{{ Auth::user()->roles->pluck('name')->first() ?? 'Pengguna' }}</span>
                    @if(Auth::user()->secretariat)
                        di regional <strong>{{ Auth::user()->secretariat->name }}</strong>
                    @endif
                </p>

                @hasanyrole('Super Admin Pusat|Admin Sekre')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-100 p-6 rounded-xl shadow-sm text-center">
                        <div class="text-4xl font-extrabold text-aat-blue mb-2">{{ $totalRelawan }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Relawan</div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-100 p-6 rounded-xl shadow-sm text-center">
                        <div class="text-4xl font-extrabold text-aat-yellow-dark mb-2">{{ $totalSekre }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Regional Aktif</div>
                    </div>
                    <div class="bg-green-50 border border-green-100 p-6 rounded-xl shadow-sm text-center">
                        <div class="text-4xl font-extrabold text-green-600 mb-2">{{ $totalKegiatan }}</div>
                        <div class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Kegiatan Terdata</div>
                    </div>
                </div>
                @endhasanyrole

                @role('Relawan')
                <div class="bg-blue-50 border-l-4 border-aat-blue p-4 rounded-r-lg mb-6">
                    <h3 class="font-bold text-aat-blue">Siap untuk berbagi cahaya hari ini?</h3>
                    <p class="text-gray-700 mt-2 text-sm">Cek menu <strong>"Cari Kegiatan"</strong> di atas untuk melihat jadwal pendampingan terdekat di regionalmu.</p>
                </div>
                @endrole

            </div>
        </div>
    </div>
</x-app-layout>