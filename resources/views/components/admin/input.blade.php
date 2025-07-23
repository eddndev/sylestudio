{{-- resources/views/components/admin/input.blade.php --}}
@props([
    'label',            // texto visible
    'name'   => null,   // opcional – puede venir solo en $attributes
    'value'  => null,   // valor por defecto / edición
])

@php
    // Nombre del campo; cae a null si ni prop ni atributo
    $fieldName = $name ?? $attributes->get('name');
@endphp

<label class="block">
    <span class="text-sm">{{ $label }}</span>

    <input
        {{ $attributes->merge([
            'class' => 'w-full border-b border-outline focus:ring-0',
            // Mantén type="text" por defecto si no se definió
            'type'  => $attributes->get('type', 'text'),
            // name solo si sabemos cuál es (evitamos atributo vacío)
            'name'  => $fieldName,
            // value: prioridad → 1º old(), 2º $value, 3º nada
            'value' => $fieldName
                        ? old($fieldName, $value)
                        : $value,
        ]) }}>
</label>
