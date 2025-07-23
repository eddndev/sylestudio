{{-- resources/views/components/primary-button.blade.php --}}
@props([
    'type' => 'submit',   // ←  valor por defecto nuevo
])

<button {{ $attributes->merge([
        'type'  => $type,         // se puede sobre-escribir en la vista
        'class' => 'btn-primary'
    ]) }}>
    {{ $slot }}
</button>
