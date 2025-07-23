@props([
    'title' => null,      // título opcional
])

<div {{ $attributes->merge([
        'class' => 'bg-surface border border-surface-variant shadow-sm p-6'
    ]) }}>

    @isset($title)
        <h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>
    @endisset

    {{ $slot }}
</div>
