@props([
    'type'     => 'text',
    'name',
    'onblack'  => false,   // true = para fondos oscuros
    'placeholder' => '',
])

@php
    $base  = 'block w-full border-b outline-none placeholder:uppercase placeholder:text-xs';
    $light = 'border-outline text-on-surface focus:ring-outline';
    $dark  = 'border-outline-variant text-on-primary placeholder:text-on-primary/60 focus:ring-on-primary';
@endphp

<input
    {{ $attributes->merge([
        'type'        => $type,
        'name'        => $name,
        'placeholder' => $placeholder,
        'class'       => $base . ' ' . ($onblack ? $dark : $light)
    ]) }}
/>
