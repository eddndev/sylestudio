@props(['node', 'isTopLevel' => false])

{{-- 
    La clase 'border-b' se aplica solo a los items de nivel superior, 
    creando una separación visual clara entre los bloques principales.
--}}
<li x-sort:item data-id="{{ $node->id }}"
    @class([
        'category-tree-item group',
        'border-b border-surface-variant' => $isTopLevel,
        'border-b-0' => !$isTopLevel, // Aseguramos que los hijos no tengan borde inferior
    ])>
    <div
        x-data="{
            edit: false,
            name: @js($node->name),
            focus() { $nextTick(() => $refs.input?.focus()) },
            save() { /* Tu lógica de guardado no cambia */
                fetch(window.routes.update({{ $node->id }}), {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': window.csrf, 'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({_method: 'PUT', name: this.name, slug: this.name.toLowerCase().replace(/[^a-z0-9]+/g,'-')})
                }).then(() => this.edit = false)
            }
        }"
        {{-- Fondos diferenciados para crear contraste entre niveles --}}
        @class([
            'flex items-center gap-3 transition-colors duration-200 px-3 py-2',
            'bg-surface hover:bg-surface-variant' => $isTopLevel,
            'bg-surface-variant hover:bg-surface' => !$isTopLevel,
        ])>
        
        <button class="cursor-move text-on-surface/40 hover:text-on-surface transition-colors"
                x-sort:handle aria-label="Arrastrar">
            <svg class="h-5 w-5"><use href="#icon-drag"/></svg>
        </button>

        <div class="flex-1">
            <template x-if="!edit">
                {{-- Los nodos con hijos en negrita para denotar que son "carpetas" --}}
                <span @dblclick="edit=true;focus()"
                      class="block w-full cursor-pointer"
                      :class="{ 'font-semibold': {{ $node->children->isNotEmpty() ? 'true' : 'false' }} }"
                      x-text="name"></span>
            </template>
            <template x-if="edit">
                <input x-ref="input" x-model="name"
                       @keydown.enter.prevent="save" @blur="save"
                       class="w-full bg-transparent p-0 border-0 border-b border-outline focus:ring-0 focus:border-on-surface"/>
            </template>
        </div>

        <div class="ml-auto flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            {{-- Sin cambios aquí --}}
            <button type="button" class="icon-btn" aria-label="Editar" @click="edit=true;focus()">
                <svg class="h-5 w-5"><use href="#icon-edit"/></svg>
            </button>
            <form method="POST" action="{{ route('admin.categories.destroy', $node) }}" onsubmit="return confirm('¿Eliminar categoría?')">
                @csrf @method('DELETE')
                <button class="icon-btn text-red-600 hover:text-red-500" aria-label="Eliminar">
                    <svg class="h-5 w-5"><use href="#icon-trash"/></svg>
                </button>
            </form>
        </div>
    </div>

    <ul x-sort="send" x-sort:group="cats"
        class="category-tree space-y-px">
        @foreach ($node->children as $child)
            {{-- La llamada recursiva sigue pasando el prop 'is-top-level' como false --}}
            <x-category-node :node="$child" :is-top-level="false"/>
        @endforeach
    </ul>
</li>