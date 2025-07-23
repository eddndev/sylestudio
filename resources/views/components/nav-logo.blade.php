{{-- 
    =================================================================
    ARCHIVO: resources/views/components/nav-logo.blade.php
    =================================================================
--}}
@props([
    // Acepta una URL para el enlace del logo, por defecto la página de inicio.
    'href' => url('/')
])

@php
    // Definimos las rutas de los dos logos
    $whiteLogoPath = 'resources/images/Logo_SyleStudio_Syle_W.png';
    $blackLogoPath = 'resources/images/Logo_SyleStudio_Syle_B.png';

    // Generamos los sets de fuentes para el logo blanco
    $white = collect(['avif', 'webp'])->mapWithKeys(fn ($format) => [
        $format => get_image($whiteLogoPath, ['w' => 120, 'format' => $format]) . " 1x, " .
                   get_image($whiteLogoPath, ['w' => 240, 'format' => $format]) . " 2x"
    ]);

    // Generamos los sets de fuentes para el logo negro
    $black = collect(['avif', 'webp'])->mapWithKeys(fn ($format) => [
        $format => get_image($blackLogoPath, ['w' => 120, 'format' => $format]) . " 1x, " .
                   get_image($blackLogoPath, ['w' => 240, 'format' => $format]) . " 2x"
    ]);
@endphp

<a {{ $attributes->merge(['class' => 'relative shrink-0 flex items-center justify-center']) }} href="{{ $href }}">
    <picture class="logo--white block logo h-full">
        <source type="image/avif" srcset="{{ $white['avif'] }}">
        <source type="image/webp" srcset="{{ $white['webp'] }}">
        
        {{-- ✅ CORRECCIÓN: La imagen ahora usa max-h-full para respetar la altura del contenedor. --}}
        <img src="{{ get_image($whiteLogoPath) }}"
             alt="{{ config('app.name') }} Logo claro" width="120" height="40"
             class="max-h-full w-auto"
             loading="eager" decoding="async" />
    </picture>

    <picture class="logo--black absolute logo inset-0 opacity-0 pointer-events-none transition-opacity duration-300 h-full flex items-center justify-center">
        <source type="image/avif" srcset="{{ $black['avif'] }}">
        <source type="image/webp" srcset="{{ $black['webp'] }}">
        
        {{-- ✅ CORRECCIÓN: La imagen ahora usa max-h-full para respetar la altura del contenedor. --}}
        <img src="{{ get_image($blackLogoPath) }}"
             alt="" aria-hidden="true" width="120" height="40"
             class="max-h-full w-auto"
             loading="eager" decoding="async" />
    </picture>
</a>
