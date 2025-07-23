@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
    @if($projects->count())
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 bg-outline gap-px" id="projects">
            @foreach ($projects as $project)
                <x-project-card :project="$project" />
            @endforeach
        </section>
    @else
        <div class="flex items-center justify-center h-[50vh]">
            <p class="text-on-surface/60">No hay proyectos para mostrar en este momento.</p>
        </div>
    @endif
@endsection