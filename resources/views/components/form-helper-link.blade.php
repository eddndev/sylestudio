{{-- resources/views/components/form-helper-link.blade.php --}}
<a {{ $attributes->merge([
    'class' => 'text-xs text-on-surface/60 hover:text-on-surface transition-colors underline'
]) }}>
    {{ $slot }}
</a>
