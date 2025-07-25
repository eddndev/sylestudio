{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sylestudio') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">

    <!-- Claro -->
    <link rel="icon" href="/favicon-light.ico" media="(prefers-color-scheme: light)" sizes="any">

    <!-- Oscuro -->
    <link rel="icon" href="/favicon-dark.ico"  media="(prefers-color-scheme: dark)"  sizes="any">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @stack('head')
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-[--color-surface] text-[--color-on-surface]">
    <x-svg-sprite />
    {{-- Navbar --}}
    @include('components.navigation')

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
    {{-- Footer --}}
    @include('sections.footer')
    @stack('scripts')
</body>
</html>
