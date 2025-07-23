{{-- resources/views/components/responsive-nav-link.blade.php --}}
@props(['active' => false])

@php
    $base = 'block w-full py-3 pl-4 text-base font-medium tracking-wide uppercase transition-opacity';
    $classes = $active
        ? "$base opacity-100"
        : "$base opacity-80 hover:opacity-100";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
