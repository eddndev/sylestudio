{{-- resources/views/admin/products/product.blade.php --}}
@extends('admin.layouts.app')

@php
    $isEdit = isset($product);
    $variantsForJs = $isEdit
        ? $product->variants->map(fn ($v) => [
            'id'       => $v->id,
            'size_id'  => $v->size_id,
            'color_id' => $v->color_id,
            'sku'      => $v->sku,
            'price'    => $v->price,
            'stock'    => $v->stock,
        ])
        : collect();
@endphp

@section('title', $isEdit ? 'Editar producto' : 'Nuevo producto')
@section('header',  $isEdit ? 'Editar producto' : 'Nuevo producto')

@section('content')
<form
    x-data="productForm({
        product            : @js($product ?? null),
        allCategories      : @js($categoriesFlat),
        selectedCategories : @js(old('categories',
                                $isEdit ? $product->categories->pluck('id') : [])),
        sizes    : @js($sizes),
        colors   : @js($colors),
        variants : @js(old('variants', $isEdit ? $variantsForJs : [])),
        existingImages : @js($existingImages ?? [])
    })"
    action="{{ $isEdit
                ? route('admin.products.update', $product)
                : route('admin.products.store') }}"
    method="post"
    enctype="multipart/form-data"
    @submit.prevent="submit"
    class="space-y-10"
>
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- ───────── 2-column layout ───────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ─── IZQUIERDA: info + imágenes ─── --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Información básica --}}
            <x-admin.card title="Información del producto">
                <div class="grid sm:grid-cols-2 gap-6">
                    <x-admin.input label="Nombre" name="name"
                                   x-model="form.name"
                                   @input="form.slug = slugify(form.name)" required/>
                    <x-admin.input label="Slug"  name="slug"
                                   x-model="form.slug" required/>
                </div>

                <x-admin.textarea label="Descripción"
                                  name="description"
                                  x-model="form.description" rows="5"/>
            </x-admin.card>

            {{-- Imágenes --}}
            <x-admin.card title="Imágenes">
                {{-- zona drop --}}
                <x-admin.dropzone x-ref="drop"
                     @files-selected="handleFiles($event.detail.files)"
                     class="h-32"/>
                {{-- previews --}}
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"
                    x-show="previews.length">
                    <template x-for="(src, i) in previews" :key="i">
                    <div class="relative group aspect-square">
                        <img :src="src" class="object-cover w-full h-full border">
                        <button type="button" class="btn-remove"
                            @click=" i < existingImages.length ? removeOld(i) : removeNew(i) ">
                            &times;
                        </button>
                    </div>
                    </template>

                </div>
            </x-admin.card>
        </div>

        {{-- ─── DERECHA: organización + variantes ─── --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Organización --}}
            <x-admin.card title="Organización">
                <x-admin.input label="Precio base" name="base_price" type="number"
                               step="0.01" x-model.number="form.base_price" required/>
                <x-admin.select label="Estado" name="status"
                                :options="$statusOptions"
                                x-model="form.status"/>
                <x-admin.select label="Género" name="gender_hint"
                                :options="$genderOptions"
                                x-model="form.gender_hint"/>
            </x-admin.card>

            {{-- Categorías --}}
            <x-admin.card title="Categorías">
                <div class="max-h-60 overflow-y-auto space-y-px border border-surface-variant">
                    @foreach($categoryTree as $node)
                        <x-admin.category-checkbox-node :node="$node" :level="0"/>
                    @endforeach
                </div>
            </x-admin.card>

            {{-- Variantes --}}
            <x-admin.card title="Variantes">
                <template x-if="!variants.length">
                    <div class="text-sm text-on-surface/60 mb-2">
                        Sin variantes - añade al menos una.
                    </div>
                </template>

                <template x-for="(v, i) in variants" :key="i">
                    <div class="variant-box">
                        <button type="button" class="btn-remove"
                                @click="variants.splice(i,1)">&times;</button>

                        {{-- Talla & Color --}}
                        <div class="grid grid-cols-2 gap-3">
                            <x-admin.select
                                label="Talla"
                                :options="$sizes->pluck('code','id')"
                                x-bind:name="'variants['+i+'][size_id]'"
                                x-model.number="v.size_id" required/>

                            <div>
                                <label class="text-sm">Color</label>
                                <x-admin.color-select
                                    :colors="$colors"
                                    x-model.number="v.color_id"
                                    x-bind:name="'variants['+i+'][color_id]'"
                                />
                            </div>
                        </div>

                        {{-- SKU / price / stock --}}
                        <x-admin.input
                            label="SKU"
                            x-bind:name="'variants['+i+'][sku]'"
                            x-model="v.sku" class="mt-3"/>

                        <div class="grid grid-cols-2 gap-3 mt-3">
                            <x-admin.input
                                label="Precio"
                                type="number" step="0.01"
                                x-bind:name="'variants['+i+'][price]'"
                                x-model.number="v.price"/>

                            <x-admin.input
                                label="Stock"
                                type="number"
                                x-bind:name="'variants['+i+'][stock]'"
                                x-model.number="v.stock"/>
                        </div>
                    </div>
                </template>

                <button type="button" class="btn-primary w-full mt-4"
                        @click="addVariant">Añadir variante</button>
            </x-admin.card>
        </div>
    </div>

    {{-- footer --}}
    <div class="flex justify-end pt-8 mt-8 border-t border-surface-variant">
        <button class="btn-primary">
            {{ $isEdit ? 'Actualizar' : 'Guardar producto' }}
        </button>
    </div>
</form>

@endsection
