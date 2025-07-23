{{-- resources/views/components/nav-link.blade.php --}}
@props(['active' => false])

@php
    $base = 'inline-flex items-center px-3 py-2 text-sm font-light tracking-wide uppercase transition-opacity';
    $classes = $active
        ? "$base opacity-100"
        : "$base opacity-80 hover:opacity-100";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
