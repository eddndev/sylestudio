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
    @endphp

    <picture>
        <source type="image/avif" srcset="{{ $srcsetAvif }}" sizes="{{ $sizes }}">
        <source type="image/webp" srcset="{{ $srcsetWebp }}" sizes="{{ $sizes }}">

        <img
            src="{{ $media->getUrl('gallery-md-webp') }}"
            alt="{{ $alt }}"
            class="{{ $class }}"
            width="{{ $media->getCustomProperty('width', '800') }}"
            height="{{ $media->getCustomProperty('height', '1200') }}"
            loading="{{ $loading }}"
            decoding="async"
            onerror="this.onerror=null;this.src='https://placehold.co/800x1200/eee/ccc?text=Error';"
        >
    </picture>
@else
    <div class="bg-gray-200 aspect-[2/3] flex items-center justify-center {{ $class }}">
        <img src="https://placehold.co/800x1200/eee/ccc?text=No+Image" alt="Placeholder" class="w-full h-full object-cover">
    </div>
@endif