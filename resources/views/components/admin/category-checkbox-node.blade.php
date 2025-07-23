@props(['node', 'level' => 0])

<label
    class="flex items-center gap-3 w-full cursor-pointer p-2
           hover:bg-surface transition-colors">
    {{-- Sangría: 1.5 rem por nivel --}}
    <span style="width: {{ $level * 1.5 }}rem;"></span>

    <input  type="checkbox"
            name="categories[]"
            value="{{ $node->id }}"
            x-model.number="form.categories"
            @change="toggleCategory({{ $node->id }}, $event.target.checked)"
            class="bg-surface border-outline text-black focus:ring-offset-surface
                   focus:ring-1 focus:ring-black">

    <span>
        {{-- Ruta completa: Padre > Hijo --}}
        {{ $node->ancestors->pluck('name')->join(' > ') }}
        {{ $node->ancestors->isNotEmpty() ? ' > ' : '' }}
        {{ $node->name }}
    </span>
</label>

@if ($node->children->isNotEmpty())
    @foreach ($node->children as $child)
        <x-admin.category-checkbox-node
            :node="$child"
            :level="$level + 1"/>
    @endforeach
@endif
