{{-- 
    =================================================================
    ARCHIVO: resources/views/components/instagram-post-card.blade.php
    =================================================================
--}}
@props(['post'])

<a href="{{ $post->url }}" 
   target="_blank" 
   rel="noopener noreferrer"
   {{-- ✅ CORRECCIÓN: Se cambia 'aspect-square' por 'aspect-[3/4]' para un estilo de columna --}}
   class="group relative block aspect-[3/5] overflow-hidden bg-surface-variant"
>
    @if ($media = $post->getFirstMedia())
        {{-- 
            Renderizamos una etiqueta <picture> para servir los formatos más optimizados.
            El componente se encarga de mostrar la imagen con sus conversiones.
        --}}
        <picture>
            <source type="image/avif" srcset="{{ $media->getUrl('display-avif') }}">
            <source type="image/webp" srcset="{{ $media->getUrl('display-webp') }}">
            <img src="{{ $media->getUrl() }}" 
                 alt="Publicación de la comunidad Syle Studio: {{ $post->title }}"
                 class="h-full w-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105"
                 loading="lazy"
                 decoding="async">
        </picture>
    @endif

    {{-- 
        Overlay que aparece al pasar el ratón (hover).
        Utiliza las clases 'group-hover' de Tailwind CSS.
    --}}
    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 p-4 text-center text-white
                opacity-0 transition-opacity duration-300 group-hover:opacity-100">
        
        {{-- Icono de Instagram --}}
        <svg class="w-8 h-8 mb-2 text-white" fill="currentColor" viewBox="0 0 24 24">
            <use xlink:href="#icon-instagram"></use>
        </svg>
        
        {{-- Título de la publicación --}}
        <p class="font-semibold leading-tight">{{ $post->title }}</p>
    </div>
</a>
