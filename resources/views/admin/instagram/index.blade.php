{{-- resources/views/admin/instagram/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Publicaciones de Comunidad')
@section('header', 'Publicaciones de Comunidad')

@section('content')
{{-- Barra superior con advertencia y botón de creación --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <p class="text-sm text-on-surface/80">
        Gestiona las 6 publicaciones que aparecen en la sección "Desde la Comunidad".
    </p>

    <a href="{{ route('admin.instagram.create') }}" class="btn-primary">
        <svg class="h-[24px] w-[24px]"><use href="#icon-plus"/></svg>
        <span>Nueva Publicación</span>
    </a>
</div>

{{-- Tabla de publicaciones --}}
<div class="w-full overflow-x-auto bg-surface border border-surface-variant shadow-sm">
    <table class="w-full text-sm">
        <thead class="text-left">
            <tr class="border-b border-surface-variant">
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80" colspan="2">Publicación</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80">URL de Instagram</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80">Visibilidad</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
            <tr class="group border-b border-surface-variant last:border-b-0 hover:bg-surface-variant/60 transition-colors duration-200">
                
                {{-- Columna de la imagen --}}
                <td class="p-4">
                    <a href="{{ route('admin.instagram.edit', $post) }}">
                        @if($post->hasMedia())
                            <img src="{{ $post->getFirstMediaUrl() }}"
                                 alt="Miniatura de {{ $post->title }}"
                                 class="h-14 w-14 object-cover border bg-surface-variant"/>
                        @else
                            {{-- Placeholder si no hay imagen --}}
                            <div class="h-14 w-14 bg-surface-variant border flex items-center justify-center">
                                <svg class="h-6 w-6 text-on-surface/30"><use href="#icon-image"/></svg>
                            </div>
                        @endif
                    </a>
                </td>

                {{-- Columna de Título --}}
                <td class="p-4">
                    <a href="{{ route('admin.instagram.edit', $post) }}" class="font-semibold text-base hover:underline">{{ $post->title }}</a>
                </td>

                {{-- Columna de la URL --}}
                <td class="p-4">
                    <a href="{{ $post->url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline flex items-center gap-1">
                        <span>Ver publicación</span>
                        <svg class="h-4 w-4"><use href="#icon-external-link"/></svg>
                    </a>
                </td>

                {{-- Columna de Visibilidad --}}
                <td class="p-4">
                    <span @class([
                        'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold',
                        'bg-green-100 text-green-800' => $post->is_visible,
                        'bg-gray-100 text-gray-800' => !$post->is_visible,
                    ])>
                        <span @class([
                            'h-1.5 w-1.5 rounded-full inline-block',
                            'bg-green-500' => $post->is_visible,
                            'bg-gray-500' => !$post->is_visible,
                        ])></span>
                        {{ $post->is_visible ? 'Visible' : 'Oculto' }}
                    </span>
                </td>

                {{-- Columna de Acciones --}}
                <td class="p-4">
                    <div class="flex gap-2 justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.instagram.edit', $post) }}" class="icon-btn" title="Editar">
                            <svg class="h-5 w-5"><use href="#icon-edit"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.instagram.destroy', $post) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta publicación?')">
                            @csrf
                            @method('DELETE')
                            <button class="icon-btn text-red-600" title="Eliminar">
                                <svg class="h-5 w-5"><use href="#icon-trash"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-on-surface/70">
                        No se encontraron publicaciones.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
