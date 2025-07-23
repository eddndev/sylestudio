// resources/js/categories.js
import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initCategoryScroll() {
  const mm = gsap.matchMedia();
  mm.add('(min-width: 640px)', () => {
    gsap.utils.toArray('[data-card]').forEach(card => {
      const label = card.querySelector('[data-label]');
      const tween = gsap.to(label, {
        y: () => card.offsetHeight - label.offsetHeight - 40,
        ease: 'none'
      });

      ScrollTrigger.create({
        trigger: card,
        start: 'top 80%',
        end: 'bottom bottom-=40',
        scrub: true,
        animation: tween,
        invalidateOnRefresh: true,
        media: '(prefers-reduced-motion: no-preference)'
      });
    });
  });

  mm.add('(max-width: 639px)', () => {
  });
}
