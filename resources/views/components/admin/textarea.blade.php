@props([
    'label',
    'name',
    'rows'      => 4,
    'value'     => null,      // para edición
])

<label class="block w-full">
    <span class="text-sm">{{ $label }}</span>
    <textarea
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->class('w-full border-b border-outline bg-surface-variant focus:border-on-surface focus:ring-0 mt-1') }}
    >{{ old($name, $value) }}</textarea>
</label>
