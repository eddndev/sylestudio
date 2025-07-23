{{-- resources/views/components/form-description.blade.php --}}
<p {{ $attributes->merge([
    'class' => 'mt-4 text-sm text-center text-on-surface/80 leading-relaxed'
]) }}>
    {{ $slot }}
</p>
