@props([
    'media',
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'sizes' => '100vw',
])

@if ($media)
    @php
        $widthMap = [
            'sm' => 400,
            'md' => 800,
            'lg' => 1200,
            'xl' => 1920,
        ];

        $srcsetAvif = collect($widthMap)
            ->map(fn($width, $size) => $media->getUrl('gallery-' . $size . '-avif') . ' ' . $width . 'w')
            ->implode(', ');

        $srcsetWebp = collect($widthMap)
            ->map(fn($width, $size) => $media->getUrl('gallery-' . $size . '-webp') . ' ' . $width . 'w')
            ->implode(', ');

        // Fallback src con webp de tamaño medio
        $fallbackSrc = $media->getUrl('gallery-md-webp');
    @endphp

    <picture>
        <source type="image/avif" srcset="{{ $srcsetAvif }}" sizes="{{ $sizes }}">
        <source type="image/webp" srcset="{{ $srcsetWebp }}" sizes="{{ $sizes }}">

        <img
            src="{{ $fallbackSrc }}"
            alt="{{ $alt }}"
            class="{{ $class }}"
            width="{{ $media->getCustomProperty('width', '800') }}"
            height="{{ $media->getCustomProperty('height', '1200') }}"
            loading="{{ $loading }}"
            decoding="async"
            fetchpriority="{{ $loading === 'eager' ? 'high' : 'auto' }}"
            onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'w-full h-full bg-gray-200 flex items-center justify-center\'><span class=\'text-gray-400\'>Error</span></div>';"
        >
    </picture>
@else
    <div class="bg-gray-200 aspect-[2/3] flex items-center justify-center {{ $class }}">
        <span class="text-gray-400 text-sm">Sin imagen</span>
    </div>
@endif