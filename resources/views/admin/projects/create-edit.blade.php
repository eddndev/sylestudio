@extends('admin.layouts.app')

@php
    $isEdit = isset($project) && $project->exists;

    $existingImages = $isEdit
        ? $project->getMedia('gallery')->map(fn ($media) => [
            'id' => $media->id,
            'url' => $media->getUrl('admin-thumb'),
            'order' => $media->order_column,
        ])->sortBy('order')->values() 
        : collect();

    $alpineConfig = [
        'isEdit' => $isEdit,
        'projectId' => $project->id ?? null,
        // El contador de procesamiento se pasa independientemente de las imágenes existentes.
        'processingCount' => session('processing_count', 0),
        'project' => [
            'title' => old('title', $project->title ?? ''),
            'slug' => old('slug', $project->slug ?? ''),
            'description' => old('description', $project->description ?? ''),
            'status' => old('status', $project->status ?? 'draft'),
            'published_at' => old('published_at', $project->published_at ? $project->published_at->format('Y-m-d\TH:i') : ''),
        ],
        'existingImages' => $existingImages,
        'oldGallery' => old('gallery') ?? [],
        'loadUrl' => url('admin/projects/uploads/load') . '/',
        'uploadUrl' => route('admin.projects.uploads.store'),
        'revertUrl' => route('admin.projects.uploads.destroy'),
        'reorderUrl' => $isEdit ? route('admin.projects.gallery.reorder', $project) : '',
    ];
@endphp

@section('title', $isEdit ? 'Editar Proyecto' : 'Nuevo Proyecto')
@section('header', $isEdit ? 'Editar Proyecto' : 'Nuevo Proyecto')

@section('content')

<script>
    const alpineProjectConfig = {!! json_encode($alpineConfig) !!};
</script>

<form
    x-data="projectForm(alpineProjectConfig)"
    @new-image-processed.window="handleNewImage($event.detail)"
    {{-- --- FIN DE LA CORRECCIÓN --- --}}
    action="{{ $isEdit ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
    method="POST"
    class="space-y-10"
    id="project-form"
>
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <x-admin.card title="Información del Proyecto">
                <div class="grid sm:grid-cols-2 gap-6">
                    <x-admin.input label="Título" name="title" x-model="form.title" @input="form.slug = window.slugify(form.title)" required />
                    <x-admin.input label="Slug" name="slug" x-model="form.slug" required />
                </div>
                <x-admin.textarea label="Descripción" name="description" x-model="form.description" rows="8" class="mt-6" />
            </x-admin.card>

            <x-admin.card title="Galería de Imágenes">
                <div wire:ignore>
                    <input type="file" x-ref="filepond" name="gallery_upload" multiple>
                </div>

                <div x-show="isProcessing" class="mt-6 p-4 text-center bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex items-center justify-center gap-2 text-blue-700">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Procesando imágenes... aparecerán aquí en tiempo real.</span>
                    </div>
                </div>

                <div x-show="galleryItems.length > 0" class="mt-6">
                    <h3 class="text-sm font-medium text-on-surface/80 mb-2">Previsualización</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <template x-for="(item, index) in galleryItems" :key="item.key || item.id">
                            <div class="relative group aspect-square bg-surface-variant border border-outline-variant">
                                <img :src="item.url" class="object-cover w-full h-full">
                                
                                <div class="absolute top-1 right-1 flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="moveItem(index, -1)" :disabled="index === 0" class="w-7 h-7 flex items-center justify-center bg-black/50 text-white rounded-full disabled:opacity-25" title="Mover arriba">↑</button>
                                    <button type="button" @click="moveItem(index, 1)" :disabled="index === galleryItems.length - 1" class="w-7 h-7 flex items-center justify-center bg-black/50 text-white rounded-full disabled:opacity-25" title="Mover abajo">↓</button>
                                </div>

                                <div class="absolute bottom-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="removeItem(item.id)" class="w-7 h-7 flex items-center justify-center bg-red-600/80 text-white rounded-full" title="Eliminar">&times;</button>
                                </div>

                                <template x-if="item.source === 'new'">
                                    <input type="hidden" name="gallery[]" :value="item.id">
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <div class="lg:col-span-1 space-y-8">
            <x-admin.card title="Organización">
                <x-admin.select label="Estado" name="status" x-model="form.status" :options="['draft' => 'Borrador', 'published' => 'Publicado']" required />
                <x-admin.input type="datetime-local" label="Fecha de Publicación" name="published_at" x-model="form.published_at" class="mt-6"
                               help="Dejar en blanco para publicar inmediatamente. Si se establece una fecha futura, el proyecto no será visible hasta esa fecha." />
            </x-admin.card>
        </div>
    </div>

    <div class="flex justify-end pt-8 mt-8 border-t border-surface-variant">
        <a href="{{ route('admin.projects.index') }}" class="btn-secondary mr-4">Cancelar</a>
        <button type="submit" class="btn-primary">
            {{ $isEdit ? 'Actualizar Proyecto' : 'Guardar Proyecto' }}
        </button>
    </div>
</form>
@endsection
