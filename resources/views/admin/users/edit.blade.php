<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-aat-blue text-2xl font-bold transition">←</a>
            <h2 class="font-semibold text-xl text-aat-blue leading-tight">
                {{ __('Edit Pengguna: ') }} <span class="text-aat-yellow">{{ $user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border-t-4 border-aat-yellow p-8">
                
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="name" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="email" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="email" name="email" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="phone_number" :value="__('Nomor WhatsApp')" class="font-bold text-gray-700 mb-1" />
                        <x-text-input id="phone_number" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="text" name="phone_number" :value="old('phone_number', $user->phone_number)" placeholder="Contoh: 081234567890" />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="secretariat_id" :value="__('Penempatan Regional')" class="font-bold text-gray-700 mb-1" />
                        <select id="secretariat_id" name="secretariat_id" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" required>
                            <option value="" disabled>-- Pilih Regional --</option>
                            @foreach($secretariats as $sekre)
                                <option value="{{ $sekre->id }}" {{ old('secretariat_id', $user->secretariat_id) == $sekre->id ? 'selected' : '' }}>{{ $sekre->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('secretariat_id')" class="mt-2" />
                    </div>

                    <div class="mb-5">
                        <x-input-label for="sianas_id" :value="__('ID SIANAS / Nomor Anggota')" class="font-bold text-gray-700 mb-1" />
                        
                        @if(auth()->user()->hasRole('Super Admin Pusat'))
                            <x-text-input id="sianas_id" class="block w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="text" name="sianas_id" :value="old('sianas_id', $user->sianas_id)" placeholder="Contoh: SIA-12345" />
                            <x-input-error :messages="$errors->get('sianas_id')" class="mt-2" />
                        @else
                            <x-text-input id="sianas_id" class="block w-full bg-gray-100 text-gray-500 border-gray-300 cursor-not-allowed rounded-md shadow-sm" type="text" name="sianas_id_readonly" :value="$user->sianas_id ?? 'Belum Ada'" readonly disabled />
                            <p class="text-xs text-gray-500 mt-1">*Hanya Super Admin Pusat yang memiliki wewenang untuk mengisi atau mengubah ID ini.</p>
                        @endif
                    </div>

                    <div class="mb-6 p-5 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-bold text-gray-800 mb-1">Ganti Password (Opsional)</h4>
                        <p class="text-xs text-gray-500 mb-4 border-b pb-2">*Kosongkan bagian ini jika tidak ingin mengubah password akun ini.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="password" :value="__('Password Baru')" class="text-gray-700 mb-1" />
                                <x-text-input id="password" class="block w-full border-gray-300" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-gray-700 mb-1" />
                                <x-text-input id="password_confirmation" class="block w-full border-gray-300" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t border-gray-100 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-800 font-bold transition">Batal</a>
                        <button type="submit" class="bg-aat-yellow hover:bg-yellow-500 text-black font-bold py-2.5 px-8 rounded-lg shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>