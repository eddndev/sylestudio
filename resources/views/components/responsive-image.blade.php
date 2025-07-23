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
@endphp

{{-- 
    La lógica condicional para el enlace <a> permanece igual.
--}}
@if ($href)
    <a href="{{ $href }}" @if($target) target="{{ $target }}" @endif class="{{ $class }}">
        <picture>
            <source type="image/avif" srcset="{{ $sources['avif'] }}" sizes="100vw">
            <source type="image/webp" srcset="{{ $sources['webp'] }}" sizes="100vw">
            <img
                src="{{ get_image("resources/images/{$key}") }}"
                alt="{{ $alt }}"
                class="w-full h-full object-cover"
                width="{{ $widths[count($widths) - 1] }}"
                height="{{ $widths[count($widths) - 1] / (16/9) }}"
                loading="{{ $loading }}"
                decoding="async"
                onerror="this.onerror=null;this.style.display='none';"
            >
        </picture>
    </a>
@else
    <picture class="{{ $class }}">
        <source type="image/avif" srcset="{{ $sources['avif'] }}" sizes="100vw">
        <source type="image/webp" srcset="{{ $sources['webp'] }}" sizes="100vw">
        <img
            src="{{ get_image("resources/images/{$key}") }}"
            alt="{{ $alt }}"
            class="w-full h-full object-cover"
            width="{{ $widths[count($widths) - 1] }}"
            height="{{ $widths[count($widths) - 1] / (16/9) }}"
            loading="{{ $loading }}"
            decoding="async"
            onerror="this.onerror=null;this.style.display='none';"
        >
    </picture>
@endif
