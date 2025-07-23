{{-- resources/views/pages/product/product.blade.php --}}
@extends('layouts.app')

@section('title', $product->name)

@php
    // Precio mostrado = mínimo de las variantes o base_price
    $displayPrice = $product->variants->min('price') ?? $product->base_price;
@endphp


@section('content')
<div class="mx-auto max-w-7xl px-6 py-16 grid lg:grid-cols-2 gap-12">

    {{-- Galería de imágenes --}}
    <div x-data>
        <div class="aspect-square overflow-hidden bg-neutral-100">
            <img x-ref="main"
                 src="{{ asset('storage/' . $product->images->first()?->src) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover"
                 loading="eager" decoding="async">
        </div>

        @if($product->images->count() > 1)
        <div class="mt-4 flex gap-2">
            @foreach($product->images as $img)
            <button type="button"
                    class="h-20 w-20 shrink-0 overflow-hidden border rounded"
                    @click="$refs.main.src='{{ asset('storage/'.$img->src) }}'">
                <img src="{{ asset('storage/' . $img->src) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Detalles --}}
    <div class="space-y-6">
        <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>

        <p class="text-xl font-bold">
            ${{ number_format((float) $displayPrice, 2) }}
        </p>


        {{-- Tallas disponibles --}}
        @php $sizes = $product->variants->pluck('size')->unique('id'); @endphp
        @if($sizes->count())
        <div>
            <span class="block text-sm font-medium mb-2">Talla</span>
            <div class="flex flex-wrap gap-2">
                @foreach($sizes as $size)
                <span class="px-3 py-1 border rounded text-sm">{{ $size->code }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Colores disponibles --}}
        @php $colors = $product->variants->pluck('color')->filter()->unique('id'); @endphp
        @if($colors->count())
        <div>
            <span class="block text-sm font-medium mb-2">Color</span>
            <div class="flex flex-wrap gap-2">
                @foreach($colors as $color)
                <span class="h-6 w-6 rounded-full border"
                      style="background-color: {{ $color->hex ?? '#000' }}"></span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Descripción --}}
        <div class="prose max-w-none text-sm">
            {!! nl2br(e($product->description)) !!}
        </div>

        {{-- Chips de categoría --}}
        <div>
            <span class="block text-xs uppercase text-neutral-500">Categorías</span>
            <div class="flex flex-wrap gap-2 mt-1">
                @foreach($product->categories as $cat)
                <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                   class="text-xs hover:underline">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
