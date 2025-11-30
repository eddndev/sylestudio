// 1. Importa GSAP y ScrollTrigger.
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// 2. Envolvemos toda la lógica en un listener para que se ejecute cuando el DOM esté listo.
document.addEventListener('DOMContentLoaded', function () {
    
    // 3. Obtenemos el contenedor de la galería. Si no existe, no hacemos nada.
    //    Esto evita errores en otras páginas que no tengan la galería.
    const galleryContainer = document.getElementById('gallery-container');
    if (!galleryContainer) {
        return;
    }

    const loader = document.getElementById('gallery-loader');
    
    // 4. Obtenemos la URL de la API desde un atributo de datos.
    const galleryUrl = galleryContainer.dataset.galleryUrl;
    if (!galleryUrl) {
        console.warn('Galería detectada pero sin URL de datos. Omitiendo carga.');
        if(loader) loader.style.display = 'none';
        return;
    }

    // --- INICIO DE EJECUCIÓN ---
    setupModal();
    buildFullGallery();

    // --- Lógica de Renderizado con Animación ---
    async function buildFullGallery() {
        try {
            const response = await fetch(galleryUrl);
            if (!response.ok) throw new Error(`HTTP error!`);
            const images = await response.json();

            if (!images || images.length === 0) {
                if(loader) loader.innerHTML = '<p class="text-on-surface/60">Esta galería no tiene imágenes.</p>';
                return;
            }

            const fragment = document.createDocumentFragment();

            images.forEach(image => {
                const link = document.createElement('a');
                link.href = image.src_full;
                // Añadimos translate-y-4 para que la animación tenga desde dónde subir
                link.className = 'gallery-item block mb-4 opacity-0 translate-y-4'; 

                const placeholder = document.createElement('div');
                placeholder.className = 'gallery-item-placeholder w-full overflow-hidden';
                if (image.height > 0) {
                    placeholder.style.aspectRatio = `${image.width} / ${image.height}`;
                }

                const picture = document.createElement('picture');
                picture.innerHTML = `
                    <source type="image/avif" srcset="${image.srcset_avif}" sizes="(max-width: 768px) 50vw, 33vw">
                    <source type="image/webp" srcset="${image.srcset_webp}" sizes="(max-width: 768px) 50vw, 33vw">
                    <img src="${image.src_fallback}" alt="${image.alt}" loading="lazy" decoding="async" class="w-full h-full object-cover opacity-0 transition-opacity duration-500">
                `;
                
                // CORRECCIÓN: Verificar si la imagen ya cargó (caché) para evitar que se quede invisible
                const img = picture.querySelector('img');
                const reveal = () => img.classList.remove('opacity-0');

                if (img.complete) {
                    reveal();
                } else {
                    img.addEventListener('load', reveal);
                    img.addEventListener('error', () => console.warn('Error cargando imagen:', image.src_fallback));
                }

                placeholder.appendChild(picture);
                link.appendChild(placeholder);
                fragment.appendChild(link);
            });
            
            galleryContainer.appendChild(fragment);

            // Disparamos evento custom por si alguien escucha
            window.dispatchEvent(new CustomEvent('gallery-ready'));
            
            // Animación con ScrollTrigger
            gsap.to('.gallery-item', {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                stagger: 0.08,
                scrollTrigger: {
                    trigger: "#gallery-container",
                    start: "top 85%", // Inicia cuando el top del contenedor está al 85% del viewport
                    toggleActions: "play none none none"
                }
            });

        } catch (error) {
            console.error('Error al construir la galería:', error);
            if(loader) loader.innerHTML = '<p class="text-red-500">Error al cargar la galería.</p>';
        }
    }

    // Re-implementar la lógica del modal aquí
    function setupModal() {
        const modal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');
        const modalCloseButton = document.getElementById('modal-close-button');

        const openModal = (imageUrl) => {
            modalImage.src = imageUrl;
            modal.classList.remove('opacity-0', 'pointer-events-none');
            document.body.style.overflow = 'hidden';
        };

        const closeModal = () => {
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.style.overflow = '';
        };

        galleryContainer.addEventListener('click', e => {
            const link = e.target.closest('a.gallery-item');
            if (link) {
                e.preventDefault();
                openModal(link.href);
            }
        });
        modalCloseButton.addEventListener('click', closeModal);
        modal.addEventListener('click', e => e.target === modal && closeModal());
        document.addEventListener('keydown', e => e.key === 'Escape' && closeModal());
    }
});