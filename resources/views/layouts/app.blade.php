{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO & Metatags --}}
    <title>@yield('title', config('app.name', 'Sylestudio')) | Your Style. Your Strength.</title>
    <meta name="description" content="@yield('description', 'SyleStudio es un estudio creativo multidisciplinario que fusiona moda, fotografía, diseño, música, animación y deporte como estilo de vida.')">
    <meta name="keywords" content="@yield('keywords', 'SyleStudio, Syle, Estudio Creativo, Diseño, Moda, Fotografía, Animación, México, Syle Studio')">
    <meta name="author" content="SyleStudio">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Sylestudio') | Your Style. Your Strength.">
    <meta property="og:description" content="@yield('description', 'Estudio creativo multidisciplinario fundado en 2024 en México. Fusionamos moda, diseño y arte.')">
    <meta property="og:image" content="@yield('og_image', asset('images/syleb.png'))">
    <meta property="og:site_name" content="SyleStudio">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    {{-- JSON-LD Schema para Entidad de Marca --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "SyleStudio",
        "alternateName": ["Syle", "Syle Studio"],
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/syleb.png') }}",
        "slogan": "Your Style. Your Strength. Your Spirit. Your Essence.",
        "description": "Estudio creativo multidisciplinario que fusiona moda, fotografía, diseño, música, animación y deporte.",
        "foundingDate": "2024",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "MX"
        }
    }
    </script>

    <link rel="icon" href="/favicon.ico" sizes="any">

    <!-- Claro -->
    <link rel="icon" href="/favicon-dark.ico" media="(prefers-color-scheme: light)" sizes="any">

    <!-- Oscuro -->
    <link rel="icon" href="/favicon-light.ico"  media="(prefers-color-scheme: dark)"  sizes="any">


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
