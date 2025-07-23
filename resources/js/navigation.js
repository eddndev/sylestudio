// resources/js/utils/navbar-scroll.js
import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * Efecto blur/color para #mainNav
 * Se invoca una sola vez cuando Alpine está listo.
 * @param {HTMLElement} nav Elemento #mainNav
 */
export function initNavbarScroll(nav) {
  // ── 1. ¿Hay hero?  ───────────────────────────────
  const hero = document.querySelector('[data-hero], #hero, .hero');
  const about = document.querySelector('#about');
  const projects = document.querySelector('#projects');
  const projectHero = document.querySelector('#project-hero');
  const isNoHeroPage = !hero && !about && !projects && !projectHero;
  if (isNoHeroPage) {
    nav.classList.add('nav--scrolled'); // nav siempre opaco
    return;                             // nada que animar
  }

  // ── 2. ScrollTrigger vivo solo en páginas con hero ─
  const st = ScrollTrigger.create({
    start: 'top -40',
    onEnter:     () => nav.classList.add('nav--scrolled'),
    onLeaveBack: () => nav.classList.remove('nav--scrolled'),
  });

  // Congela el estado cuando el drawer móvil está abierto
  window.addEventListener('nav:open',  () => st.disable());
  window.addEventListener('nav:close', () => st.enable());
}
