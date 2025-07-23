@props([
    'project',
])

<article class="group relative isolate aspect-[2/3] overflow-hidden">
    
    <div class="absolute inset-0 -z-10 h-full w-full transition-transform duration-500 ease-in-out group-hover:scale-105">
        <x-media-image
            :media="$project->getFirstMedia('gallery')"
            :alt="'Imagen del proyecto ' . $project->title"
            class="h-full w-full object-cover"
            sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
        />
    </div>

    <div class="pointer-events-none absolute bottom-0 left-0 p-4 md:p-6 text-white bg-gradient-to-t from-black/70 via-black/40 to-transparent w-full">
        <h2 class="font-teko text-4xl md:text-5xl font-bold leading-none">
            {{ $project->title }}
        </h2>
        <small class="block mt-2 text-sm md:text-base">
            Ver proyecto ->
        </small>
    </div>

    <a href="{{ route('projects.show', $project) }}" class="absolute inset-0 z-10" aria-label="Ver detalles del proyecto {{ $project->title }}"></a>
</article>