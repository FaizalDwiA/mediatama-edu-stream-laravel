<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Meta Tags & SEO -->
        <meta name="description" content="Edustream - Platform Streaming Video Edukasi Interaktif Terlengkap.">
        <meta name="keywords" content="edustream, edukasi, streaming, video, belajar, tutorial, interaktif">
        <meta name="author" content="Edustream">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="Edustream - Media Streaming & Interactive Education">
        <meta property="og:description" content="Tonton video edukasi interaktif dan tingkatkan keahlianmu di Edustream.">
        <meta property="og:image" content="{{ asset('img/logo/logo1.png') }}">

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo/logo1.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo/logo1.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/logo/logo1.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
