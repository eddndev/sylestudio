{{-- resources/views/components/admin/stat-card.blade.php --}}

@props(['label', 'value', 'icon'])

<div {{ $attributes->class([
    // Estética y Espaciado
    'bg-surface text-on-surface border border-outline', // Quitamos 'rounded-lg'. Usamos bg-surface y borde más sutil
    'p-6 flex items-center gap-5',                      // Aumentamos padding a p-6 y el gap a 5
    
    // Interactividad (opcional pero recomendado)
    'transition-colors duration-200 hover:bg-surface-variant'
    ]) }}>

    {{-- Icono --}}
    <span class="inline-flex h-12 w-12 items-center justify-center
                   bg-black text-white shrink-0">  {{-- Quitamos 'rounded-md' --}}
        <svg class="h-6 w-6"><use xlink:href="#{{ $icon }}"/></svg>
    </span>

    {{-- Contenido de texto --}}
    <div class="flex flex-col">
        <span class="text-3xl font-semibold leading-none">{{ $value }}</span>
        {{-- Mejoramos legibilidad del label --}}
        <span class="text-sm uppercase tracking-wider text-on-surface/80">{{ $label }}</span>
    </div>
</div>