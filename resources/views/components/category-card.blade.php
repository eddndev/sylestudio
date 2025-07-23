@props([
    'slug',
    'title',
    'sub' => 'SHOP NOW',
    'srcBase',
])

@php
    $widths  = [800, 1600];
    $formats = ['avif', 'webp'];

    $sets = collect($formats)->mapWithKeys(fn ($format) => [
        $format => collect($widths)->map(fn ($w) =>
            get_image($srcBase, ['w' => $w, 'format' => $format]) . " {$w}w"
        )->implode(', ')
    ]);
@endphp

<article
    class="category-card group relative isolate h-[120vw] md:h-[80vh] overflow-hidden"
    data-card>
    {{-- Imagen responsive --}}
    <picture class="absolute inset-0 -z-10 h-full w-full">
        {{-- CORRECCIÓN: Ajustar el atributo `sizes` para que coincida con el layout --}}
        <source type="image/avif" srcset="{{ $sets['avif'] }}" sizes="(min-width: 768px) 50vw, 100vw">
        <source type="image/webp" srcset="{{ $sets['webp'] }}" sizes="(min-width: 768px) 50vw, 100vw">
        
        <img    src="{{ get_image($srcBase) }}"
                alt="{{ $title }} hero"
                class="h-full w-full object-cover" loading="lazy" decoding="async">
    </picture>

    {{-- Texto --}}
    <div class="card-label pointer-events-none"
         data-label>
        <h2 class="font-teko text-4xl sm:text-6xl md:text-4xl xl:text-7xl font-bold leading-none">
            {{ $title }}
        </h2>
        <p class="text-xs tracking-wide">{{ $sub }}</p>
    </div>

    {{-- Link pantalla completa --}}
    <a href="{{ url($slug) }}" class="absolute inset-0 z-10" aria-label="{{ $title }}"></a>
</article>