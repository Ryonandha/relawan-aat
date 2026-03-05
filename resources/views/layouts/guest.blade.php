<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Relawan AAT') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-aat-text antialiased bg-aat-gray selection:bg-aat-yellow selection:text-aat-blue">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[url('https://www.transparenttextures.com/patterns/diagmonds-light.png')]">
            
            <div class="mb-6 text-center">
                <a href="/">
                    <img src="{{ asset('storage/logo/logo-AAT.png') }}" alt="Logo Anak-Anak Terang" class="h-24 w-auto mx-auto drop-shadow-lg mb-2">
                </a>
                <h2 class="mt-4 text-2xl font-bold text-aat-blue">Portal Relawan AAT</h2>
                <p class="text-gray-500 text-sm">Masuk untuk melanjutkan ke sistem</p>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-8 bg-white shadow-xl overflow-hidden sm:rounded-2xl border-t-8 border-aat-yellow relative">
                {{ $slot }}
            </div>

            <div class="mt-8 text-sm text-gray-500 font-medium">
                &copy; {{ date('Y') }} Yayasan Anak-Anak Terang
            </div>
        </div>
    </body>
</html>