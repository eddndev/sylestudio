@props([
    'label',                    // texto visible
    'name'    => null,          // opcional: puede venir en $attributes
    'options' => [],            // ['valor'=>'Texto']
    'value'   => null,          // valor seleccionado (ej. modo edición)
])

@php
    // Si no llega por prop, lo tomamos de los atributos HTML
    $fieldName = $name ?? $attributes->get('name');
@endphp

<label class="block">
    <span class="text-sm">{{ $label }}</span>

    <select
        name="{{ $fieldName }}"
        {{ $attributes->merge(['class' =>
            'w-full border-b border-outline focus:ring-0']) }}>

        @foreach($options as $k => $text)
            {{-- old() sólo funciona si tenemos el nombre del campo --}}
            <option value="{{ $k }}"
                @selected(old($fieldName, $value) == $k)>
                {{ $text }}
            </option>
        @endforeach
    </select>
</label>
