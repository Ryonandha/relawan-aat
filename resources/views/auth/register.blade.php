<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-aat-blue">Pendaftaran Relawan Baru</h2>
        <p class="text-sm text-gray-600">Mari bergabung bersama Anak-Anak Terang</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Nomor Telepon / WhatsApp')" />
            <x-text-input id="phone_number" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="text" name="phone_number" :value="old('phone_number')" required placeholder="Contoh: 081234567890" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="secretariat_id" :value="__('Regional Penempatan (Sekretariat)')" />
            <select id="secretariat_id" name="secretariat_id" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" required>
                <option value="" disabled selected>-- Pilih Regional Terdekat --</option>
                @foreach($secretariats as $sekre)
                    <option value="{{ $sekre->id }}">{{ $sekre->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('secretariat_id')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aat-blue" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4 bg-aat-blue hover:bg-blue-800">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>