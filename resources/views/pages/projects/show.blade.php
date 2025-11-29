{{-- resources/views/pages/projects/show.blade.php --}}

@extends('layouts.app')

@section('title', $project->title)

{{-- Pre-carga de recursos críticos para máxima prioridad de carga --}}
@php
    $heroImage = $project->getFirstMedia('gallery');
@endphp

@if ($heroImage)
    @push('head')
        {{-- Fuentes para la animación de glitch --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Permanent+Marker&family=Staatliches&family=Gloria+Hallelujah&family=Covered+By+Your+Grace&family=Special+Elite&family=Anton&family=Oswald&family=Rubik+Mono+One&display=swap" rel="stylesheet">

        {{-- Preload de imagen hero con múltiples formatos --}}
        <link rel="preload" as="image" href="{{ $heroImage->getUrl('gallery-xl-webp') }}" type="image/webp" fetchpriority="high">
        <link rel="preload" as="image" href="{{ $heroImage->getUrl('gallery-xl-avif') }}" type="image/avif" fetchpriority="high">
    @endpush
@endif


@push('styles')
<style>
    :root {
      /* Fuentes "callejeras" para el efecto de glitch */
      --f0: "Bangers", cursive;
      --f1: "Staatliches", sans-serif;
      --f2: "Permanent Marker", cursive;
      --f3: "Gloria Hallelujah", cursive;
      --f4: "Covered By Your Grace", cursive;
      --f5: "Special Elite", cursive;
      --f6: "Anton", sans-serif;
      --f7: "Oswald", sans-serif;
      --f8: "Rubik Mono One", sans-serif;
    }
    .gallery-item-placeholder {
        display: block;
        background-color: #f0f0f0;
        break-inside: avoid-column;
    }
    .modal-overlay {
        transition: opacity 0.3s ease;
    }
    /* Estilos para la animación de caracteres del título del Hero */
    #hero-title .char {
        display: inline-block;
        transform: translateY(120%) skewY(8deg);
        opacity: 0;
    }
    /* Capa de ruido/grano opcional para dar textura */
    .hero-grain::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJub2lzZSI+PGZlVHVyYnVsZW5jZSB0eXBlPSJmcmFjdGFsTm9pc2UiIGJhc2VGcmVxdWVuY3k9IjAuOCIgbnVtT2N0YXZlcz0iMyIgc3RpdGNoVGlsZXM9InN0aXRjaCIvPjwvZmlsdGVyPjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIiBmaWx0ZXI9InVybCgjbnoaXNlKSIgb3BhY2l0eT0iMC4xIi8+PC9zdmc+);
        opacity: .12;
        mix-blend-mode: overlay;
        pointer-events: none;
    }
    /* ✅ CORRECCIÓN: Estilos para posicionar correctamente las capas del glitch */
    #hero-title-wrapper { position: relative; display: inline-block; }
    #hero-title { position: relative; z-index: 2; }
    .glitch-layer {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        mix-blend-mode: difference;
        opacity: 0; /* Ocultas por defecto */
    }
</style>
@endpush

@section('content')
    {{-- 1. SECCIÓN HERO CON ESTRUCTURA SVG --}}
    <section id="project-hero" class="relative h-screen overflow-hidden bg-black">
        {{-- SPRAY SVG --}}
        <svg class="absolute inset-0 w-full h-full"
             viewBox="0 0 1920 1080" preserveAspectRatio="xMidYMid slice">
            <defs>
                <filter id="sprayNoise" x="-20%" y="-20%" width="140%" height="140%">
                    <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" result="noise"/>
                    <feDisplacementMap in="SourceGraphic" in2="noise" scale="15"/>
                </filter>
                <mask id="sprayMask" maskUnits="userSpaceOnUse" maskContentUnits="userSpaceOnUse">
                    <rect width="1920" height="1080" fill="black"/>
                    <path id="sprayStroke"
                          d="M-100,540 Q960,700 2020,540"
                          stroke="white" stroke-width="350" stroke-linecap="round" fill="none"
                          filter="url(#sprayNoise)"/>
                    <rect id="maskFill" width="1920" height="1080" fill="white" opacity="0"/>
                </mask>
            </defs>
            @if ($heroImage)
                {{-- Imagen principal con fallback --}}
                <image id="hero-svg-image"
                       href="{{ $heroImage->getUrl('gallery-xl-webp') }}"
                       x="0" y="0" width="1920" height="1080"
                       preserveAspectRatio="xMidYMid slice"
                       mask="url(#sprayMask)"
                       onerror="this.href.baseVal='{{ $heroImage->getUrl() }}'"/>
            @endif
        </svg>

        {{-- TÍTULO (wrapper + capas glitch absolutas) --}}
        <div class="relative z-40 flex h-full items-center justify-center text-center p-4">
            <div id="hero-title-wrapper">
                <h1 id="hero-title"
                    class="font-teko text-7xl md:text-9xl lg:text-[10rem] text-white uppercase leading-none overflow-hidden"
                    data-title="{{ $project->title }}"></h1>
                {{-- Capas glitch, vacías al inicio y posicionadas absolutamente por CSS --}}
                <h1 id="hero-title-A" class="glitch-layer" aria-hidden="true"></h1>
                <h1 id="hero-title-B" class="glitch-layer" aria-hidden="true"></h1>
            </div>
        </div>
        <div class="hero-grain absolute inset-0 z-30 pointer-events-none"></div>
    </section>

    {{-- 2. SECCIÓN GALERÍA (sin cambios en su estructura) --}}
    <div class="bg-surface text-on-surface">
        <div class="container mx-auto py-16 px-4">
            <div id="gallery-container" class="columns-2 md:columns-3 gap-4"></div>
        </div>
    </div>

    {{-- 3. MODAL (sin cambios en su estructura) --}}
    <div id="image-modal" class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 p-4 opacity-0 pointer-events-none">
        <div class="relative max-w-4xl max-h-full">
            <img id="modal-image" src="" alt="Vista ampliada" class="block max-w-full max-h-[90vh] object-contain">
        </div>
        <button id="modal-close-button" class="absolute top-4 right-4 text-white text-4xl font-bold">&times;</button>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  if (window.__heroInit) return;
  window.__heroInit = true;

  // ---------- ELEMENTOS ----------
  const heroTitle        = document.getElementById('hero-title');
  const wrapper          = document.getElementById('hero-title-wrapper');
  const sprayStroke      = document.getElementById('sprayStroke');
  const maskFill         = document.getElementById('maskFill');
  const turbulence       = document.querySelector('#sprayNoise feTurbulence');
  const galleryContainer = document.getElementById('gallery-container');

  if (!heroTitle || !wrapper || !sprayStroke || !maskFill || !window.gsap) return;

  // ---------- POOL DE FUENTES ----------
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

  // ---------- CREAR CAPAS GLITCH ----------
  let layerA, layerB;
  function createGlitchLayers(html) {
    layerA = document.createElement('div');
    layerB = document.createElement('div');
    layerA.id = 'hero-title-A';
    layerB.id = 'hero-title-B';
    layerA.className = 'glitch-layer';
    layerB.className = 'glitch-layer';
    layerA.setAttribute('aria-hidden', 'true');
    layerB.setAttribute('aria-hidden', 'true');
    layerA.innerHTML = html;
    layerB.innerHTML = html;
    wrapper.appendChild(layerA);
    wrapper.appendChild(layerB);

    // Estado inicial totalmente oculto y fuera del flujo
    gsap.set([layerA, layerB], {
      position: 'absolute',
      top: 0, left: 0,
      width: '100%', height: '100%',
      pointerEvents: 'none',
      opacity: 0,
      visibility: 'hidden',
      color: getComputedStyle(heroTitle).color
    });
  }

  // ---------- HELPERS ----------
  function splitTitle() {
    const t = heroTitle.dataset.title || heroTitle.textContent.trim();
    const html = t.split('').map(c =>
      `<span class="char inline-block">${c === ' ' ? '&nbsp;' : c}</span>`
    ).join('');
    heroTitle.innerHTML = html;
    createGlitchLayers(html);
  }

  function applyInitialFonts() {
    const charsO = gsap.utils.toArray('#hero-title .char');
    const charsA = gsap.utils.toArray('#hero-title-A .char');
    const charsB = gsap.utils.toArray('#hero-title-B .char');
    charsO.forEach((ch, i) => {
      const f = randFont();
      ch.style.fontFamily     = f;
      charsA[i].style.fontFamily = f;
      charsB[i].style.fontFamily = f;
    });
  }

  // ---------- HERO TIMELINE ----------
  function playHeroTL() {
    const chars = gsap.utils.toArray('#hero-title .char');
    const pathLen = sprayStroke.getTotalLength();

    gsap.set(sprayStroke, { strokeDasharray: pathLen, strokeDashoffset: pathLen });
    gsap.set(chars, { yPercent: 120, skewY: 8, opacity: 0 });

    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl.to(sprayStroke, {
        strokeDashoffset: 0,
        duration: 1.25,
        ease: 'power2.inOut'
      })
      .to(turbulence, {
        attr: { baseFrequency: 0.6 },
        duration: 0.3,
        repeat: 2,
        yoyo: true,
        ease: 'sine.inOut'
      }, '<')
      .to(chars, {
        yPercent: 0,
        skewY: 0,
        opacity: 1,
        duration: 1.0,
        stagger: 0.035,
        ease: 'expo.out'
      }, '-=0.7')
      .to(maskFill, { opacity: 1, duration: 0.3 }, '>-0.15')
      .add(startGlitchLoop, '>-0.05');
  }

  // ---------- GLITCH LOOP ----------
  function startGlitchLoop() {
    const charsO = gsap.utils.toArray('#hero-title .char');
    const charsA = gsap.utils.toArray('#hero-title-A .char');
    const charsB = gsap.utils.toArray('#hero-title-B .char');

    const setClipA = gsap.quickSetter(layerA, 'clipPath');
    const setClipB = gsap.quickSetter(layerB, 'clipPath');

    // --- Una ráfaga individual ---
    function burst() {
        gsap.set([layerA, layerB], { autoAlpha: 1 });

        // slices
        const tA = gsap.utils.random(0, 70), bA = gsap.utils.random(0, 70);
        const tB = gsap.utils.random(0, 70), bB = gsap.utils.random(0, 70);
        setClipA(`inset(${tA}% 0 ${bA}% 0)`);
        setClipB(`inset(${tB}% 0 ${bB}% 0)`);

        gsap.set([layerA, layerB], {
        x: () => gsap.utils.random(-6, 6),
        filter: 'contrast(180%) brightness(125%) hue-rotate(8deg)'
        });

        // subset de letras
        const idxs    = gsap.utils.shuffle([...Array(charsO.length).keys()]).slice(0, gsap.utils.random(5, 14));
        const subsets = [charsO, charsA, charsB].map(arr => idxs.map(i => arr[i]));

        subsets.forEach(sub => {
        gsap.set(sub, {
            fontFamily   : () => fontPool[Math.floor(Math.random() * fontPool.length)],
            letterSpacing: () => gsap.utils.random(-0.06, 0.14) + 'em',
            skewX        : () => gsap.utils.random(-16, 16),
            x            : () => gsap.utils.random(-4, 4),
            filter       : 'contrast(165%) saturate(140%)'
        });
        });

        gsap.to(subsets.flat(), {
        clearProps: 'fontFamily,letterSpacing,skewX,x,filter',
        duration : 0.07,
        delay    : 0.07
        });

        gsap.to([layerA, layerB], {
        x: 0, filter: 'none', clipPath: 'inset(0% 0 0% 0)', autoAlpha: 0,
        duration: 0.09, delay: 0.09
        });
    }

    // --- Un cluster de múltiples ráfagas ---
    function runCluster() {
        const bursts = gsap.utils.random(2, 4, 1);          // cuántas ráfagas seguidas
        const gap    = gsap.utils.random(0.05, 0.12);       // separación entre ráfagas

        for (let i = 0; i < bursts; i++) {
        gsap.delayedCall(i * gap, burst);
        }

        // programa el siguiente cluster con pausa aleatoria
        gsap.delayedCall(gap * bursts + gsap.utils.random(0.7, 1.8), runCluster);
    }

    runCluster();
    }


  // ---------- GALERÍA ----------
  async function buildFullGallery() {
    if (!galleryContainer) return;
    try {
      const res = await fetch(`{{ route('projects.gallery', $project) }}`);
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const images = await res.json();
      if (!images?.length) {
        galleryContainer.innerHTML = '<p class="text-on-surface/60">Esta galería no tiene imágenes.</p>';
        return;
      }
      const frag = document.createDocumentFragment();
      images.forEach(img => {
        const a  = document.createElement('a');
        a.href   = img.src_full;
        a.className = 'gallery-item block mb-4 opacity-0 translate-y-4';

        const ph = document.createElement('div');
        ph.className = 'gallery-item-placeholder w-full overflow-hidden';
        if (img.height > 0) ph.style.aspectRatio = `${img.width} / ${img.height}`;

        const picture = document.createElement('picture');
        picture.innerHTML = `
          <source type="image/avif" srcset="${img.srcset_avif}" sizes="(max-width: 768px) 50vw, 33vw">
          <source type="image/webp" srcset="${img.srcset_webp}" sizes="(max-width: 768px) 50vw, 33vw">
          <img src="${img.src_fallback}" alt="${img.alt}" loading="lazy" decoding="async"
               class="w-full h-full object-cover opacity-0 transition-opacity duration-500">
        `;
        const imgEl = picture.querySelector('img');
        imgEl.onload = () => imgEl.classList.remove('opacity-0');
        imgEl.onerror = () => {
          imgEl.onerror = null;
          imgEl.classList.remove('opacity-0');
          imgEl.style.backgroundColor = '#e5e7eb';
          imgEl.style.minHeight = '200px';
        };

        ph.appendChild(picture);
        a.appendChild(ph);
        frag.appendChild(a);
      });
      galleryContainer.appendChild(frag);
    } catch (err) {
      console.error('Galería:', err);
    }
  }

  function setupGalleryAnimation() {
    if (!galleryContainer) return;
    gsap.to('.gallery-item', {
      opacity: 1, y: 0, duration: 0.8, ease: 'power2.out', stagger: 0.08,
      scrollTrigger: {
        trigger: "#gallery-container",
        start: "top 85%",
        toggleActions: "play none none none"
      }
    });
  }

  // ---------- MODAL ----------
  function setupModal() {
    const modal = document.getElementById('image-modal');
    if (!modal) return;
    const modalImage = document.getElementById('modal-image');
    const modalCloseButton = document.getElementById('modal-close-button');

    const openModal = (url) => {
      modalImage.src = url;
      modal.classList.remove('opacity-0', 'pointer-events-none');
      document.body.style.overflow = 'hidden';
    };
    const closeModal = () => {
      modal.classList.add('opacity-0', 'pointer-events-none');
      document.body.style.overflow = '';
    };

    galleryContainer?.addEventListener('click', e => {
      const link = e.target.closest('a.gallery-item');
      if (link) { e.preventDefault(); openModal(link.href); }
    });
    modalCloseButton.addEventListener('click', closeModal);
    modal.addEventListener('click', e => e.target === modal && closeModal());
    document.addEventListener('keydown', e => e.key === 'Escape' && closeModal());
  }

  // ---------- RUN ----------
  splitTitle();
  applyInitialFonts();
  playHeroTL();
  setupModal();
  buildFullGallery().then(setupGalleryAnimation);
});
</script>

@endpush
