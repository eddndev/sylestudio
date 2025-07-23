@props([
    'colors',              // collection de App\Models\Color
    'name' => null,        // se sigue usando para el input hidden
])

@php
$palette = $colors->map(fn($c) => [
    'id' => (int) $c->id,
    'name' => $c->name,
    'hex' => $c->hex,
])->values()->all();
@endphp

<!-- ① propagamos TODOS los atributos (x-model, class…) -->
<div {{ $attributes->merge(['class' => 'relative']) }}
     x-data='{
         open:false,
         colors:@json($palette),
         selectedId:null,            /* Alpine lo rellenará vía x-model */
         pick(id){ this.selectedId=id; this.$dispatch("input",id); this.open=false },
         label(){ return this.colors.find(c=>c.id==this.selectedId) ?? {name:"—",hex:"#ccc"} }
     }'
     x-modelable="selectedId">

    <!-- ② input oculto sólo con name -->
    <input type="hidden" name="{{ $name }}" x-model.number="selectedId">

    <button type="button" @click="open=!open"
            class="flex items-center gap-2 w-full text-left">
        <span class="h-5 w-5 border"
              :style="{backgroundColor:label().hex}"></span>
        <span class="flex-1" x-text="label().name"></span>
        <svg class="h-4 w-4 transition-transform"
             :class="open && 'rotate-180'"><use href="#icon-chevron-down"/></svg>
    </button>

    <div x-show="open" @click.outside="open=false" x-transition
         class="absolute z-50 mt-1 w-48 bg-surface border shadow max-h-60 overflow-y-auto">
        <template x-for="c in colors" :key="c.id">
            <div @click="pick(c.id)"
                 class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-surface-variant">
                <span class="h-5 w-5 border"
                      :style="{backgroundColor:c.hex}"></span>
                <span x-text="c.name"></span>
            </div>
        </template>
    </div>
</div>
