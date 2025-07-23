{{-- resources/views/admin/instagram/create-edit.blade.php --}}

@extends('admin.layouts.app')

@php
    $isEdit = isset($post) && $post->exists;
    $existingImage = null;
    if ($isEdit && $post->hasMedia()) {
        $media = $post->getFirstMedia();
        $existingImage = ['id' => $media->id, 'url' => $media->getUrl()];
    }

    $alpineConfig = [
        'isEdit' => $isEdit,
        'post' => [
            'title' => old('title', $post->title ?? ''),
            'url' => old('url', $post->url ?? 'https://www.instagram.com/p/'),
            'is_visible' => (bool) old('is_visible', $post->is_visible ?? false),
        ],
        'existingImage' => $existingImage,
        'uploadUrl' => route('admin.instagram.uploads.store'),
        'revertUrl' => route('admin.instagram.uploads.destroy'),
    ];
@endphp

@section('title', $isEdit ? 'Editar Publicación' : 'Nueva Publicación')
@section('header', $isEdit ? 'Editar Publicación' : 'Nueva Publicación')

@section('content')

<script>
    const alpineInstagramConfig = {!! json_encode($alpineConfig) !!};
</script>

<form
    x-data="instagramForm(alpineInstagramConfig)"
    {{-- ✅ CORRECCIÓN: Se añade x-init para sincronizar el estado del checkbox al cargar --}}
    x-init="
        if ($refs.isVisibleCheckbox) {
            $refs.isVisibleCheckbox.checked = form.is_visible;
        }
    "
    action="{{ $isEdit ? route('admin.instagram.update', $post) : route('admin.instagram.store') }}"
    method="POST"
    class="space-y-10"
>
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Columna principal de contenido --}}
        <div class="lg:col-span-2 space-y-8">
            <x-admin.card title="Información de la Publicación">
                <x-admin.input 
                    label="Título" 
                    name="title" 
                    x-model="form.title"
                    required 
                />
                <x-admin.input 
                    type="url"
                    label="URL de Instagram" 
                    name="url" 
                    x-model="form.url"
                    class="mt-6"
                    required 
                />
            </x-admin.card>

            <x-admin.card title="Imagen">
                {{-- MODO EDICIÓN: Muestra la imagen actual y un botón para reemplazar --}}
                <div x-show="isEditMode && !isReplacing" class="space-y-4">
                    <label class="block text-sm font-medium text-on-surface/80">Imagen Actual</label>
                    <div class="relative w-48 aspect-square">
                        <img :src="form.existingImageUrl" class="w-full h-full object-cover border bg-surface-variant">
                    </div>
                    <button type="button" @click="startReplacing" class="btn-secondary">
                        Reemplazar Imagen
                    </button>
                </div>

                {{-- MODO CREACIÓN o REEMPLAZO: Muestra FilePond --}}
                <div x-show="!isEditMode || isReplacing">
                    <div wire:ignore>
                        <input type="file" x-ref="filepond" name="image_upload">
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Columna lateral --}}
        <div class="lg:col-span-1 space-y-8">
            <x-admin.card title="Publicación">
                {{-- Se quita x-model y se añade x-ref y @change para un control explícito --}}
                <x-admin.checkbox 
                    label="Visible en la página" 
                    name="is_visible"
                    x-ref="isVisibleCheckbox"
                    @change="form.is_visible = $event.target.checked"
                    help="Marca esta casilla para que la publicación aparezca en la sección 'Desde la Comunidad'."
                />
            </x-admin.card>
        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="flex justify-end pt-8 mt-8 border-t border-surface-variant">
        <a href="{{ route('admin.instagram.index') }}" class="btn-secondary mr-4">Cancelar</a>
        <button type="submit" class="btn-primary">
            {{ $isEdit ? 'Actualizar Publicación' : 'Guardar Publicación' }}
        </button>
    </div>
</form>
@endsection
