@props([
    'key',
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'widths' => [400, 800, 1600],
    'formats' => ['avif', 'webp'],
    'href' => null,
    'target' => null,
])

@php
    $sources = collect($formats)->mapWithKeys(function ($format) use ($widths, $key) {
        $set = collect($widths)->map(function ($w) use ($format, $key) {
            return get_image("resources/images/{$key}", ['w' => $w, 'format' => $format]) . " {$w}w";
        })->implode(', ');

        return [$format => $set];
    });

    // URL de fallback JPG para máxima compatibilidad
    $fallbackSrc = get_image("resources/images/{$key}");
    $maxWidth = $widths[count($widths) - 1];
@endphp

@if ($href)
    <a href="{{ $href }}" @if($target) target="{{ $target }}" @endif class="{{ $class }}">
        <picture>
            <source type="image/avif" srcset="{{ $sources['avif'] }}" sizes="100vw">
            <source type="image/webp" srcset="{{ $sources['webp'] }}" sizes="100vw">
            <img
                src="{{ $fallbackSrc }}"
                alt="{{ $alt }}"
                class="w-full h-full object-cover"
                width="{{ $maxWidth }}"
                height="{{ round($maxWidth / (16/9)) }}"
                loading="{{ $loading }}"
                decoding="async"
                fetchpriority="{{ $loading === 'eager' ? 'high' : 'auto' }}"
                onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'w-full h-full bg-gray-200 flex items-center justify-center\'><span class=\'text-gray-400\'>Error</span></div>';"
            >
        </picture>
    </a>
@else
    <picture class="{{ $class }}">
        <source type="image/avif" srcset="{{ $sources['avif'] }}" sizes="100vw">
        <source type="image/webp" srcset="{{ $sources['webp'] }}" sizes="100vw">
        <img
            src="{{ $fallbackSrc }}"
            alt="{{ $alt }}"
            class="w-full h-full object-cover"
            width="{{ $maxWidth }}"
            height="{{ round($maxWidth / (16/9)) }}"
            loading="{{ $loading }}"
            decoding="async"
            fetchpriority="{{ $loading === 'eager' ? 'high' : 'auto' }}"
            onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'w-full h-full bg-gray-200 flex items-center justify-center\'><span class=\'text-gray-400\'>Error</span></div>';"
        >
    </picture>
@endif
