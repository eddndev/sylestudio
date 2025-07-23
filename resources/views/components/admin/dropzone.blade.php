{{-- resources/views/components/admin/dropzone.blade.php --}}
@props(['on' => 'files-selected', 'class' => ''])

<div
    {{ $attributes->merge([
        'class' => collect([
            'flex flex-col items-center justify-center text-center
             border-2 border-dashed border-outline-variant
             cursor-pointer transition-colors duration-200',
            $class,
        ])->implode(' '),

        'x-data' => '{}',
        '@click' => '$refs.file.click()',

        /* highlight drag --------------------------------------------------- */
        '@dragover.prevent'  => '$el.classList.add("bg-surface-variant")',
        '@dragleave.prevent' => '$el.classList.remove("bg-surface-variant")',

        /* drop: quitamos highlight y emitimos archivos --------------------- */
        '@drop.prevent' => '
            $el.classList.remove("bg-surface-variant");
            $dispatch("'.$on.'", { files: $event.dataTransfer.files });
        ',
    ]) }}>

    {{-- icono + texto --}}
    <svg class="h-10 w-10 text-outline-variant"><use href="#icon-upload"/></svg>
    <p class="mt-2 text-sm text-on-surface/80">
        Arrastra imágenes aquí o
        <span class="font-semibold text-black">haz clic</span> para seleccionar
    </p>

    {{-- input[file] invisible --}}
    <input type="file" multiple x-ref="file" class="hidden"
           @change='$dispatch("{{ $on }}", { files: $event.target.files })'>
</div>
