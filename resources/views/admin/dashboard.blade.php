@extends('admin.layouts.app')

@section('title','Dashboard')
@section('header','Dashboard')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <x-admin.stat-card label="Total products"
                        :value="\App\Models\Product::count()"
                        icon="shopping-bag" />
        <x-admin.stat-card label="Variants"
                        :value="\App\Models\ProductVariant::count()"
                        icon="layers" />
        <x-admin.stat-card label="Categories"
                        :value="\App\Models\Category::count()"
                        icon="folder" />
    </div>
@endsection
