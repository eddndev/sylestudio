{{-- resources/views/components/form-title.blade.php --}}
@props(['level' => 1]) {{-- por si algún día quieres usar h2/h3 --}}
@php($tag = 'h'.$level)
<{{ $tag }} {{ $attributes->merge([
    'class' => 'text-2xl font-semibold text-center uppercase tracking-wide text-on-surface'
]) }}>
    {{ $slot }}
</{{ $tag }}>
