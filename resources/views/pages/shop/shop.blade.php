{{-- resources/views/pages/shop.blade.php --}}
@extends('layouts.app')

@section('title', 'Shop')

@php
    /** helper para elegir precio a mostrar:
     *   - si hay variantes → el menor precio
     *   - si no, base_price
     */
    function productPrice($product) {
        return optional($product->variants->min('price'))
               ?: $product->base_price;
    }
@endphp

@section('content')
<section class="mx-auto max-w-7xl px-6 py-16 space-y-10">

    {{-- Barra de búsqueda --}}
    <form method="GET" class="max-w-md">
        <input name="search"
               value="{{ request('search') }}"
               placeholder="Buscar producto…"
               class="w-full border-b bg-transparent py-2 placeholder:text-neutral-400 focus:ring-0" />
    </form>

    {{-- Grid de productos --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <a href="{{ route('product.show', $product) }}"  {{-- crea después la ruta show --}}
               class="group block">
                <div class="aspect-square overflow-hidden bg-neutral-100">
                    <img src="{{ asset('storage/' . $product->mainImage?->src) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition group-hover:scale-105"
                         loading="lazy" decoding="async">
                </div>

                <h3 class="mt-2 text-sm font-medium tracking-tight">
                    {{ $product->name }}
                </h3>
                <p class="text-sm font-semibold">
                    ${{ number_format((float) $product->display_price, 2) }}
                </p>
            </a>
        @empty
            <p class="col-span-full text-center text-sm text-neutral-500">
                No hay productos que coincidan con tu búsqueda.
            </p>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div>
        {{ $products->links() }}
    </div>

</section>
@endsection
