@extends('admin.layouts.app')

@section('title', 'Productos')
@section('header', 'Productos')

@section('content')
{{-- Barra superior --------------------------------------------------- --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    {{-- Mejora de UX: La barra de búsqueda ahora tiene un ícono para mayor claridad --}}
    <div class="relative flex-1">
        <svg class="absolute top-1/2 -translate-y-1/2 left-3 h-5 w-5 text-on-surface/50 pointer-events-none"><use href="#icon-search"/></svg>
        <input x-data x-model.debounce.400ms="search"
               @input.debounce.400="$dispatch('search', $event.target.value)"
               name="search" value="{{ request('search') }}"
               placeholder="Buscar por nombre o SKU…"
               class="w-full sm:w-72 border-b bg-transparent pl-10 focus:ring-0 focus:border-on-surface"/>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        <svg class="h-[24px] w-[24px]"><use href="#icon-plus"/></svg>
        <span>Nuevo Producto</span>
    </a>
</div>

{{-- Tabla con diseño mejorado ------------------------------------------ --}}
<div class="w-full overflow-x-auto bg-surface border border-surface-variant shadow-sm">
    <table class="w-full text-sm">
        {{-- Cabecera con mejor estilo tipográfico --}}
        <thead class="text-left">
            <tr class="border-b border-surface-variant">
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80" colspan="2">Producto</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80">Variantes</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80">Estado</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $p)
            {{-- CORRECCIÓN: Se añade la clase 'group' al <tr> para habilitar group-hover --}}
            <tr class="group border-b border-surface-variant last:border-b-0 hover:bg-surface-variant/60 transition-colors duration-200">
                
                {{-- Miniatura más grande --}}
                <td class="p-4">
                    <a href="{{ route('admin.products.edit', $p) }}">
                        <img src="{{ $p->main_image_url }}"
                             alt="{{ $p->name }}"
                             class="h-14 w-14 object-cover border bg-surface-variant"/>
                    </a>
                </td>

                {{-- Nombre, SKU y Precio Base para mayor información --}}
                <td class="p-4">
                    <a href="{{ route('admin.products.edit', $p) }}" class="font-semibold text-base hover:underline">{{ $p->name }}</a>
                    <div class="text-xs text-on-surface/60">
                        <span>Precio base: ${{ number_format($p->base_price, 2) }}</span>
                    </div>
                </td>

                {{-- Indicadores visuales de colores de variantes --}}
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-1">
                            @foreach ($p->variants->unique('color_id')->take(5) as $variant)
                                @if ($variant->color)
                                    <span class="h-5 w-5 rounded-full border-2 border-surface" 
                                          style="background-color: {{ $variant->color->hex }};"
                                          title="{{ $variant->color->name }}">
                                    </span>
                                @endif
                            @endforeach
                        </div>
                        @if($p->variants_count > 5)
                            <span class="text-xs text-on-surface/70">+{{ $p->variants_count - 5 }}</span>
                        @endif
                    </div>
                </td>

                {{-- Badge de estado (con correcciones del usuario) --}}
                <td class="p-4">
                    <span @class([
                            'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold',
                            'bg-green-100 text-green-800' => $p->status === 'active',
                            'bg-amber-100 text-amber-800' => $p->status === 'draft',
                            'bg-gray-200 text-gray-800' => $p->status === 'archived',
                        ])>
                        <span @class([
                                'h-1.5 w-1.5 rounded-full inline-block',
                                'bg-green-500' => $p->status === 'active',
                                'bg-amber-500' => $p->status === 'draft',
                                'bg-gray-500' => $p->status === 'archived'
                            ])></span>
                        {{ ucfirst($p->status) }}
                    </span>
                </td>

                {{-- Acciones aparecen en hover para una UI más limpia --}}
                <td class="p-4">
                    <div class="flex gap-2 justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.products.edit', $p) }}" class="icon-btn tooltip" title="Editar">
                            <svg class="h-5 w-5"><use href="#icon-edit"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('¿Eliminar producto?')">
                            @csrf @method('DELETE')
                            <button class="icon-btn text-red-600 tooltip" title="Eliminar">
                                <svg class="h-5 w-5"><use href="#icon-trash"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-on-surface/70">
                        No se encontraron productos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection
