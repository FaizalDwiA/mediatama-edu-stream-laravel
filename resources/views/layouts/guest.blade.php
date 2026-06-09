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

        <!-- Fonts & CSS -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="auth-body">
        <div class="auth-container">
            <div class="auth-logo-wrapper">
                <a href="/">
                    <x-application-logo class="auth-logo" />
                </a>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
