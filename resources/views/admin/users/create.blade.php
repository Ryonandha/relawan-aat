<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-aat-blue text-2xl font-bold">←</a>
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Tambah Admin Regional Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-yellow p-8">
                
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nama Pengurus / Admin')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: Admin Sekre Malang" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Alamat Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="Contoh: malang@aat.or.id" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="secretariat_id" :value="__('Tugas Regional (Sekre)')" />
                        <select id="secretariat_id" name="secretariat_id" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" required>
                            <option value="" disabled selected>-- Pilih Regional yang akan dipegang --</option>
                            @foreach($secretariats as $sekre)
                                <option value="{{ $sekre->id }}">{{ $sekre->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('secretariat_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <x-input-label for="password" :value="__('Password Sementara')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-100">
                        <x-primary-button class="px-8 py-3 text-sm">
                            {{ __('Simpan Akun Admin') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>