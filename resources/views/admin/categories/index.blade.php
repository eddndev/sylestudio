@extends('admin.layouts.app')

@section('title','Categories')      @section('header','Categories')

@section('content')
<div x-data="categoryTree()" class="max-w-3xl mx-auto">
    {{-- Árbol --------------------------------------------------------- --}}
    <ul id="catTree"
        x-sort="send"
        x-sort:group="cats"
        class="space-y-2">
        @foreach ($categories as $cat)
            <x-category-node :node="$cat" :is-top-level="true" />
        @endforeach
    </ul>



    {{-- Alta rápida ---------------------------------------------------- --}}
    <form @submit.prevent="create" class="mt-6 flex gap-3 items-end">
        <input x-model="newName" placeholder="Nueva categoría"
               class="flex-1 border-b border-outline focus:ring-0"/>
        <button class="btn-primary">Añadir</button>
    </form>
</div>
@endsection
