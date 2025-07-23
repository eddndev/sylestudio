{{-- 
    =================================================================
    ARCHIVO: resources/views/components/admin/checkbox.blade.php
    =================================================================
--}}
@props([
    'name',
    'label' => '',
    'checked' => false, // Se usa como fallback si no se proporciona un x-model
    'help' => '',
])

<div class="flex items-start">
    <div class="flex items-center h-5">
        {{-- 
            ✅ CORRECCIÓN: Se utiliza $attributes->merge() para pasar dinámicamente
            atributos como x-model, :checked, o @change desde la vista padre
            directamente al input. Esto hace el componente mucho más flexible.
        --}}
        <input 
            id="{{ $name }}" 
            name="{{ $name }}" 
            type="checkbox" 
            value="1"
            {{ $attributes->merge([
                'class' => 'focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded',
                'checked' => $checked
            ]) }}
        >
    </div>
    <div class="ml-3 text-sm">
        <label for="{{ $name }}" class="font-medium text-on-surface">{{ $label }}</label>
        
        @if ($help)
            <p class="text-on-surface/70">{{ $help }}</p>
        @endif
    </div>
</div>
