{{-- 
    =================================================================
    ARCHIVO 1: VISTA PRINCIPAL
    resources/views/pages/about-us.blade.php
    =================================================================
--}}
@extends('layouts.app')

@section('title', 'Sobre Nosotros')
@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Permanent+Marker&family=Staatliches&family=Gloria+Hallelujah&family=Covered+By+Your+Grace&family=Special+Elite&family=Anton&family=Oswald&family=Rubik+Mono+One&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root{
  --f0:"Bangers",cursive;
  --f1:"Staatliches",sans-serif;
  --f2:"Permanent Marker",cursive;
  --f3:"Gloria Hallelujah",cursive;
  --f4:"Covered By Your Grace",cursive;
  --f5:"Special Elite",cursive;
  --f6:"Anton",sans-serif;
  --f7:"Oswald",sans-serif;
  --f8:"Rubik Mono One",sans-serif;
}
#about-title .char{
  display:inline-block;
  transform:translateY(120%) skewY(8deg);
  opacity:0;
}
.glitch-layer{
  position:absolute;
  inset:0;
  z-index:1;
  pointer-events:none;
  mix-blend-mode:difference;
  opacity:0;
  visibility:hidden;
}
@media (prefers-reduced-motion: reduce){
  .glitch-layer{display:none!important;}
}
</style>
@endpush

@section('content')
{{-- HERO ------------------------------------------------------------------}}
<section class="relative h-[100vh] lg:h-screen overflow-hidden" id="about">
    
    <x-responsive-image
        key="nosotros.jpg"
        alt="Syle Studio – Sobre Nosotros"
        class="absolute w-full h-full"
        img-class="object-top"
        loading="eager"
    />

    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6
                bg-gradient-to-b from-black/60 via-black/50 to-black/60">
        
        {{-- TÍTULO CON WRAPPER + CAPAS GLITCH --}}
        <div id="about-title-wrapper" class="relative inline-block overflow-hidden">
            <h1 id="about-title"
                class="font-teko text-6xl md:text-8xl font-bold tracking-wider text-white uppercase leading-none"
                data-title="SYLE STUDIO"></h1>

            {{-- Capas duplicadas para el glitch --}}
            <h1 id="about-title-A" class="glitch-layer" aria-hidden="true"></h1>
            <h1 id="about-title-B" class="glitch-layer" aria-hidden="true"></h1>
        </div>

        <p class="text-xl md:text-2xl font-light tracking-widest text-white/90 mt-2 flex gap-2">
            <span
                x-data="typeLoop(
                    ['Tu Estilo.', 'Tu Fuerza.', 'Tu Espíritu.', 'Tu Esencia.'],
                    100, 1800, 50
                )"
                class="font-semibold text-white relative"
            >
                <span x-text="display"></span>
                <span class="absolute right-0 top-0 bottom-0 -mr-2 w-0.5 bg-white animate-pulse"></span>
            </span>
        </p>
    </div>
</section>

{{-- CONTENIDO ------------------------------------------------------------- --}}
<main class="bg-surface">
    {{-- Sección 1: El Manifiesto --}}
    <section class="mx-auto max-w-4xl px-6 py-20 md:py-32 text-center">
        <h2 class="font-teko text-5xl md:text-6xl font-semibold uppercase tracking-wide text-on-surface">
            Más que una Marca, un Manifiesto
        </h2>
        <div class="prose prose-xl mx-auto mt-6 text-on-surface/80">
            <p>
            Fundado en 2024 en México, Syle Studio nació como un proyecto enfocado en impulsar el crecimiento personal en cada aspecto de la vida. Nuestra visión no es solo construir una marca, sino crear un estilo de vida — un movimiento que combine creatividad, fuerza, espíritu y equilibrio, todo en armonía con uno mismo, a través del estilo, el deporte, la fotografía, el diseño, la música, la animación y todas aquellas formas en las que el ser humano transforma su pasión en arte.
            </p>
            <p>
                Creemos que cada persona tiene una historia que vale la pena contar. En Syle Studio, queremos ser tanto el apoyo como la chispa que ayude a dar vida a esas historias. Nos esforzamos por construir un vínculo alimentado por el mismo amor y dedicación que tú pones en aquello que te apasiona.
            </p>
        </div>
    </section>

    {{-- Sección con 3 imágenes debajo del manifiesto --}}
    <section class="pb-20 md:pb-32">
        <div class="w-full grid grid-cols-1 md:grid-cols-3">
            <div class="aspect-[1/1] bg-surface-variant">
                 <x-responsive-image 
                    key="about-manifiesto-1.jpg" 
                    alt="Próximamente: nuevos proyectos de Syle Studio"
                    class="w-full h-full"
                    href="{{ route('coming-soon') }}"
                />
            </div>
            <div class="aspect-[1/1] bg-surface-variant">
                 <x-responsive-image 
                    key="about-manifiesto-2.jpg" 
                    alt="La visión de Syle Studio"
                    class="w-full h-full"
                />
            </div>
            <div class="aspect-[1/1] bg-surface-variant">
                 <x-responsive-image 
                    key="about-manifiesto-3.jpg" 
                    alt="Proyectos de construcción de marca de Syle Studio"
                    class="w-full h-full"
                    href="/projects/queue-1"
                />
            </div>
        </div>
    </section>

    {{-- ✅ SECCIÓN DE PUBLICACIONES DE LA COMUNIDAD (IMPLEMENTACIÓN FINAL) --}}
    <section class="py-20 md:py-32 text-center bg-surface-variant">
         <h2 class="font-teko text-5xl md:text-6xl font-semibold uppercase tracking-wide text-on-surface">
            NUESTRA FILOSOFÍA
        </h2>
        <p class="mt-4 max-w-2xl mx-auto text-lg text-on-surface/70 mb-16">
            Un vistazo a las historias y momentos que nuestra comunidad comparte.
        </p>

        {{-- La variable $instagramPosts es inyectada por el PageController --}}
        @if (isset($instagramPosts) && $instagramPosts->isNotEmpty())
            {{-- ✅ CORRECCIÓN: Se actualiza la grilla con las nuevas clases responsivas y sin espaciado --}}
            <div class="grid grid-cols-2 md:grid-cols-3 mxl:grid-cols-4 2xl:grid-cols-6 gap-0">
                @foreach ($instagramPosts as $post)
                    {{-- ✅ CORRECCIÓN: Se añade un div contenedor para la lógica de visibilidad --}}
                    {{-- Oculta el 5to y 6to post en 'xl', pero los vuelve a mostrar en '2xl' --}}
                    <div @if($loop->index >= 4) class="hidden xl:block 2xl:block" @if($loop->index >= 4) class="xl:hidden 2xl:block" @endif @endif>
                        <x-instagram-post-card :post="$post" />
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-on-surface/70">Aún no hay publicaciones para mostrar. ¡Síguenos en Instagram!</p>
        @endif
    </section>

    {{-- Sección 4: Llamada a la acción final --}}
    <section class="mx-auto max-w-4xl px-6 py-20 md:py-32 text-center">
        <h2 class="font-teko text-5xl md:text-6xl font-semibold uppercase tracking-wide text-on-surface">
            Únete al Movimiento
        </h2>
        <div class="prose prose-xl mx-auto mt-6 text-on-surface/80">
            <p>
                Syle Studio es una comunidad para creadores, atletas, pensadores y soñadores. Encuentra un espacio para crecer, conectar y transformar tu pasión en tu legado.
            </p>
        </div>
        <div class="mt-10">
            <a href="{{ route('shop') }}" class="inline-block bg-primary text-on-primary font-bold py-3 px-8 uppercase tracking-wider hover:bg-primary/90 transition-colors">Explora Nuestras Colecciones</a>
        </div>
    </section>
</main>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  if (window.__aboutGlitchInit) return;
  window.__aboutGlitchInit = true;

  const titleEl   = document.getElementById('about-title');
  const wrapper   = document.getElementById('about-title-wrapper');
  if (!titleEl || !wrapper || !window.gsap) return;

  // ---------- Fuentes ----------
  const fontPool = [
    "'Bangers', cursive",
    "'Permanent Marker', cursive",
    "'Staatliches', sans-serif",
    "'Gloria Hallelujah', cursive",
    "'Covered By Your Grace', cursive",
    "'Special Elite', cursive",
    "'Anton', sans-serif",
    "'Oswald', sans-serif",
    "'Rubik Mono One', sans-serif"
  ];
  const randFont = () => fontPool[Math.floor(Math.random() * fontPool.length)];

  // ---------- Crear capas glitch ----------
  let layerA, layerB;
  function createGlitchLayers(html){
    layerA = document.getElementById('about-title-A');
    layerB = document.getElementById('about-title-B');
    layerA.innerHTML = html;
    layerB.innerHTML = html;

    gsap.set([layerA, layerB], {
      position:'absolute', top:0,left:0,width:'100%',height:'100%',
      pointerEvents:'none', opacity:0, visibility:'hidden',
      color: getComputedStyle(titleEl).color
    });
  }

  // ---------- Partir el título en chars ----------
  function splitTitle(){
    const t = titleEl.dataset.title || titleEl.textContent.trim();
    const html = t.split('').map(c =>
      `<span class="char">${c === ' ' ? '&nbsp;' : c}</span>`
    ).join('');
    titleEl.innerHTML = html;
    createGlitchLayers(html);
  }

  // ---------- Asignar fuentes iniciales ----------
  function applyInitialFonts(){
    const o = gsap.utils.toArray('#about-title .char');
    const a = gsap.utils.toArray('#about-title-A .char');
    const b = gsap.utils.toArray('#about-title-B .char');
    o.forEach((ch,i)=>{
      const f = randFont();
      ch.style.fontFamily  = f;
      a[i].style.fontFamily= f;
      b[i].style.fontFamily= f;
    });
  }

  // ---------- Timeline de entrada ----------
  function playIntro(){
    const chars = gsap.utils.toArray('#about-title .char');
    gsap.set(chars,{ yPercent:120, skewY:8, opacity:0 });

    gsap.timeline({ defaults:{ ease:'power3.out' } })
      .to(chars,{
        yPercent:0, skewY:0, opacity:1,
        duration:1.0, stagger:0.035, ease:'expo.out'
      });
  }

  // ---------- Loop de glitch ----------
  function startGlitchLoop(){
    const charsO = gsap.utils.toArray('#about-title .char');
    const charsA = gsap.utils.toArray('#about-title-A .char');
    const charsB = gsap.utils.toArray('#about-title-B .char');

    const setClipA = gsap.quickSetter(layerA,'clipPath');
    const setClipB = gsap.quickSetter(layerB,'clipPath');

    function burst(){
      gsap.set([layerA, layerB], { autoAlpha:1 });

      const tA = gsap.utils.random(0,70),
            bA = gsap.utils.random(0,70),
            tB = gsap.utils.random(0,70),
            bB = gsap.utils.random(0,70);

      setClipA(`inset(${tA}% 0 ${bA}% 0)`);
      setClipB(`inset(${tB}% 0 ${bB}% 0)`);

      gsap.set([layerA, layerB], {
        x: () => gsap.utils.random(-6, 6),
        filter:'contrast(180%) brightness(125%) hue-rotate(8deg)'
      });

      const idxs = gsap.utils.shuffle([...Array(charsO.length).keys()])
                     .slice(0, gsap.utils.random(5,14));

      [charsO, charsA, charsB].forEach(arr=>{
        idxs.forEach(i=>{
          const el = arr[i];
          gsap.set(el,{
            fontFamily   : randFont(),
            letterSpacing: gsap.utils.random(-0.06,0.14)+'em',
            skewX        : gsap.utils.random(-16,16),
            x            : gsap.utils.random(-4,4),
            filter       : 'contrast(165%) saturate(140%)'
          });
        });
      });

      gsap.to([charsO,charsA,charsB].flat(),{
        clearProps:'fontFamily,letterSpacing,skewX,x,filter',
        duration:0.07, delay:0.07
      });

      gsap.to([layerA, layerB],{
        x:0, filter:'none', clipPath:'inset(0% 0 0% 0)', autoAlpha:0,
        duration:0.09, delay:0.09
      });
    }

    function runCluster(){
      const bursts = gsap.utils.random(2,4,1);
      const gap    = gsap.utils.random(0.05,0.12);

      for(let i=0;i<bursts;i++){
        gsap.delayedCall(i*gap, burst);
      }
      gsap.delayedCall(gap*bursts + gsap.utils.random(0.7,1.8), runCluster);
    }

    runCluster();
  }

  // ---------- Init ----------
  splitTitle();
  applyInitialFonts();
  playIntro();
  startGlitchLoop();
});
</script>
@endpush
