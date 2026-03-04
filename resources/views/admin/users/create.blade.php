<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-aat-blue text-2xl font-bold transition">←</a>
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Tambah Admin Regional Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-blue p-8">
                
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-5">
                        <x-input-label for="name" :value="__('Nama Pengurus / Admin')" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="name" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="email" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="secretariat_id" :value="__('Tugas Regional (Sekretariat)')" class="font-bold text-gray-700 mb-1" />
                        <select id="secretariat_id" name="secretariat_id" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" required>
                            <option value="" disabled selected>-- Pilih Regional --</option>
                            @foreach($secretariats as $sekre)
                                <option value="{{ $sekre->id }}" {{ old('secretariat_id') == $sekre->id ? 'selected' : '' }}>{{ $sekre->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('secretariat_id')" class="mt-2" />
                    </div>

                    <div class="mb-6 p-5 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 border-b pb-2">Pengaturan Password</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="text-gray-700 mb-1" />
                                <x-text-input id="password" class="block w-full border-gray-300" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 mb-1" />
                                <x-text-input id="password_confirmation" class="block w-full border-gray-300" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-800 font-bold transition">Batal</a>
                        <button type="submit" class="bg-aat-blue hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-lg shadow-md transition">
                            Simpan Admin Baru
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>