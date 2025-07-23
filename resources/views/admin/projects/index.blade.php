@extends('admin.layouts.app')

@section('title', 'Proyectos')
@section('header', 'Proyectos')

@section('content')
{{-- Barra superior con búsqueda y botón de creación --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    {{-- TODO: Implementar la lógica de búsqueda en el controlador --}}
    <div class="relative flex-1">
        <svg class="absolute top-1/2 -translate-y-1/2 left-3 h-5 w-5 text-on-surface/50 pointer-events-none" aria-hidden="true"><use href="#icon-search"/></svg>
        <input name="search"
               value="{{ request('search') }}"
               placeholder="Buscar por título..."
               class="w-full sm:w-72 border-b bg-transparent pl-10 focus:ring-0 focus:border-on-surface"/>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn-primary">
        <svg class="h-[24px] w-[24px]"><use href="#icon-plus"/></svg>
        <span>Nuevo Proyecto</span>
    </a>
</div>

{{-- Tabla de proyectos --}}
<div class="w-full overflow-x-auto bg-surface border border-surface-variant shadow-sm">
    <table class="w-full text-sm">
        <thead class="text-left">
            <tr class="border-b border-surface-variant">
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80" colspan="2">Proyecto</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80">Galería</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80">Estado</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider text-on-surface/80"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
            <tr class="group border-b border-surface-variant last:border-b-0 hover:bg-surface-variant/60 transition-colors duration-200">
                
                {{-- Columna de la miniatura --}}
                <td class="p-4">
                    <a href="{{ route('admin.projects.edit', $project) }}">
                        @if($project->hasMedia('gallery'))
                            <img src="{{ $project->getFirstMediaUrl('gallery', 'admin-thumb') }}"
                                 alt="Miniatura de {{ $project->title }}"
                                 class="h-14 w-14 object-cover border bg-surface-variant"/>
                        @else
                            {{-- Placeholder si no hay imagen --}}
                            <div class="h-14 w-14 bg-surface-variant border flex items-center justify-center">
                                <svg class="h-6 w-6 text-on-surface/30"><use href="#icon-image"/></svg>
                            </div>
                        @endif
                    </a>
                </td>

                {{-- Columna de Título y Slug --}}
                <td class="p-4">
                    <a href="{{ route('admin.projects.edit', $project) }}" class="font-semibold text-base hover:underline">{{ $project->title }}</a>
                    <div class="text-xs text-on-surface/60">
                        <span>{{ $project->slug }}</span>
                    </div>
                </td>

                {{-- Columna de la Galería --}}
                <td class="p-4">
                    <span class="text-on-surface/80">{{ $project->media_count }} imágen{{ $project->media_count !== 1 ? 'es' : '' }}</span>
                </td>

                {{-- Columna del Estado --}}
                <td class="p-4">
                    <span @class([
                        'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold',
                        'bg-green-100 text-green-800' => $project->status === 'published',
                        'bg-amber-100 text-amber-800' => $project->status === 'draft',
                    ])>
                        <span @class([
                            'h-1.5 w-1.5 rounded-full inline-block',
                            'bg-green-500' => $project->status === 'published',
                            'bg-amber-500' => $project->status === 'draft',
                        ])></span>
                        {{ ucfirst($project->status) }}
                    </span>
                </td>

                {{-- Columna de Acciones --}}
                <td class="p-4">
                    <div class="flex gap-2 justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="icon-btn" title="Editar">
                            <svg class="h-5 w-5"><use href="#icon-edit"/></svg>
                        </a>
                        {{-- El botón de previsualizar abre la ruta pública en una nueva pestaña --}}
                        <a href="{{ route('projects.show', $project) }}" target="_blank" class="icon-btn" title="Previsualizar">
                            <svg class="h-5 w-5"><use href="#icon-external-link"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este proyecto? Esta acción no se puede deshacer.')">
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
                        No se encontraron proyectos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
<div class="mt-6">
    {{ $projects->links() }}
</div>
@endsection
