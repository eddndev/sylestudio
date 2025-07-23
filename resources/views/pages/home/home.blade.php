{{-- resources/views/pages/home.blade.php --}}
@extends('layouts.app')

@php
    $widths  = [800, 1600];
    $formats = ['avif', 'webp'];

    $sources = collect($formats)->mapWithKeys(function ($format) use ($widths) {
        $set = collect($widths)->map(function ($w) use ($format) {
            return get_image("resources/images/hero.jpg", ['w' => $w, 'format' => $format]) . " {$w}w";
        })->implode(', ');

        return [$format => $set];
    });
@endphp

@section('content')
<section id="hero" class="relative h-[100vh] overflow-hidden">
    {{-- 2. Picture responsivo --}}
    <picture class="absolute inset-0 w-full h-full">
        <source
            type="image/avif"
            srcset="{{ $sources['avif'] }}"
            sizes="100vw">

        <source
            type="image/webp"
            srcset="{{ $sources['webp'] }}"
            sizes="100vw">

        <img
            {{-- CORRECCIÓN 1: Usar el helper para la imagen de fallback --}}
            src="{{ get_image('resources/images/hero.jpg') }}"
            alt="Modelo vistiendo Sylestudio"
            class="w-full h-full object-cover"
            width="1600"
            height="900"
            loading="eager"
            decoding="async">
    </picture>

    {{-- 3. Overlay + contenido --}}
    <div class="absolute inset-0 bg-black/40"></div>
</section>

<section id="categories" class="grid grid-cols-1 md:grid-cols-2 gap-1 p-1">
    {{-- CORRECCIÓN 2: Añadir la extensión .jpg a las rutas base --}}
    <x-category-card slug="about-us"    title="NOSOTROS"     sub="CONÓCENOS"   src-base="resources/images/about.jpg" />
    <x-category-card slug="coming-soon"     title="PROXIMAMENTE"      sub="????"  src-base="resources/images/coming1.jpg" />
    <x-category-card slug="projects" title="PROYECTOS" sub="NUESTROS PROYECTOS" src-base="resources/images/projects.jpg" />
    <x-category-card slug="coming-soon"     title="?????"       sub=""  src-base="resources/images/coming2.jpg" />
</section>

@include('sections.join')
@endsection