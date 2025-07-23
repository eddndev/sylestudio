import './bootstrap';

import Alpine from 'alpinejs';
import sort from '@alpinejs/sort'
import collapse from '@alpinejs/collapse'
import anchor from '@alpinejs/anchor';
import slugify from 'slugify';
import { initNavbarScroll } from './navigation';   // solo importa la función, no el módulo completo dos veces
import { initCategoryScroll } from './categories';
import projectForm from './project-form';
import instagramForm from './instagram-form';'./instagram-form';
import './project-gallery';
import { gsap } from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger'; // Si lo usas en otras partes

window.gsap = gsap;
gsap.registerPlugin(ScrollTrigger); // Registra los plugins que necesites

window.Alpine = Alpine;
window.slugify = (str) => slugify(str, {
    lower: true,
    strict: true,
    trim: true,
    replacement: '-',
    locale: 'es',
});

Alpine.plugin(sort)
Alpine.plugin(collapse)
Alpine.plugin(anchor)

window.categoryTree = () => ({
    newName: '',

    async create () {
        if (!this.newName.trim()) return

        await fetch(window.routes.store, {
            method : 'POST',
            headers: {
              'X-CSRF-TOKEN': window.csrf,
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body : new URLSearchParams({
                name : this.newName,
                slug : this.slugify(this.newName) + '-' + Date.now()
            })
        })

        location.reload()
    },
    send () {
        console.log('SEND called')
        const build = ul => [...ul.children].map((li, i) => ({
            id          : +li.dataset.id,
            order       : i,
            children    :build(li.querySelector(':scope>ul')||document.createElement('ul'))
        }))

        fetch(window.routes.reorder, {
            method  : 'POST',
            headers : {
                'X-CSRF-TOKEN': window.csrf,
                'Content-Type': 'application/json'
            },
            body    : JSON.stringify({ tree: build(document.getElementById('catTree')) })
        })
    },
    slugify (s) { return s.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'') }
})
// app.js
document.addEventListener('alpine:init', () => {

    Alpine.data('typeLoop', (words, typeSpeed = 100, pause = 1500, eraseSpeed = 50) => ({
        display: '',
        currentWord: '',
        wordIndex: 0,
        charIndex: 0,
        isErasing: false,

        init() {
            this.currentWord = words[this.wordIndex];
            this.type();
        },

        type() {
            if (this.isErasing) {
                this.display = this.currentWord.substring(0, this.charIndex - 1);
                this.charIndex--;
            } 
            else {
                this.display = this.currentWord.substring(0, this.charIndex + 1);
                this.charIndex++;
            }

            if (!this.isErasing && this.charIndex === this.currentWord.length) {
                this.isErasing = true;
                setTimeout(() => this.type(), pause); // Pausa antes de borrar
            } 
            else if (this.isErasing && this.charIndex === 0) {
                this.isErasing = false;
                this.wordIndex = (this.wordIndex + 1) % words.length; // Pasa a la siguiente palabra
                this.currentWord = words[this.wordIndex];
                setTimeout(() => this.type(), typeSpeed);
            } 
            else {
                const speed = this.isErasing ? eraseSpeed : typeSpeed;
                setTimeout(() => this.type(), speed);
            }
        }
    }));
    Alpine.data('productForm', (init = {}) => ({
        form: {
            id          : init.product?.id ?? null,
            name        : init.product?.name ?? '',
            slug        : init.product?.slug ?? '',
            description : init.product?.description ?? '',
            base_price  : init.product?.base_price ?? 0,
            status      : init.product?.status ?? 'draft',
            gender_hint : init.product?.gender_hint ?? 'unisex',
            categories  : (init.selectedCategories ?? []).map(Number),
        },

        allCategories   : init.allCategories ?? [],
        variants        : init.variants?.length
                            ? init.variants
                            : [{ size_id:'', color_id:'', sku:'', price:0, stock:0 }],

        existingImages  : [...(init.existingImages ?? [])], // [{id,url}]
        files           : [],                               // File objects nuevos
        deletedImageIds : [],                               // ids para eliminar

        previews : [...(init.existingImages ?? []).map(i => i.url)],

        init() {
            this.buildCategoryMaps();
        },

        buildCategoryMaps() {
            this.parentOf   = {};
            this.childrenOf = {};
            this.allCategories.forEach(c => {
                this.parentOf[c.id] = c.parent_id;
                if (c.parent_id) (this.childrenOf[c.parent_id] ||= []).push(c.id);
            });
        },

        getDescendants(id) {
            const list = [], stack = this.childrenOf[id] ? [...this.childrenOf[id]] : [];
            while (stack.length) {
                const cid = stack.pop();
                list.push(cid);
                if (this.childrenOf[cid]) stack.push(...this.childrenOf[cid]);
            }
            return list;
        },

        toggleCategory(id, checked) {
            id = Number(id);
            if (checked) {
                for (let p = this.parentOf[id]; p; p = this.parentOf[p]) {
                    if (!this.form.categories.includes(p)) this.form.categories.push(p);
                }
            } else {
                const toRemove = this.getDescendants(id);
                this.form.categories = this.form.categories.filter(
                    cid => !toRemove.includes(cid)
                );
            }
        },

        addVariant() {
            this.variants.push({ size_id:'', color_id:'', sku:'', price:0, stock:0 });
        },

        handleFiles(list) {
            if (!list) return;
            [...list].forEach(f => {
                this.files.push(f);
                this.previews.push(URL.createObjectURL(f));
            });
        },

        removeOld(idx) {
            this.deletedImageIds.push(this.existingImages[idx].id);
            URL.revokeObjectURL(this.previews[idx]);
            this.existingImages.splice(idx, 1);
            this.previews.splice(idx, 1);
        },

        removeNew(idx) {
            const offset = idx - this.existingImages.length;
            URL.revokeObjectURL(this.previews[idx]);
            this.files.splice(offset, 1);
            this.previews.splice(idx, 1);
        },

        async submit() {
            const fd = new FormData();

            Object.entries(this.form).forEach(([k,v]) => {
                if (['categories','id'].includes(k)) return;
                fd.append(k, v);
            });

            if (this.form.id) fd.append('_method', 'PUT');

            this.form.categories.forEach(id => fd.append('category_ids[]', id));

            const safeVariants = this.variants.map(v => ({
                ...v,
                color_id : v.color_id || null,
                size_id  : v.size_id  || null,
            }));
            fd.append('variants', JSON.stringify(safeVariants));

            this.deletedImageIds.forEach(id => fd.append('deleted_image_ids[]', id));

            this.files.forEach(f => fd.append('images[]', f));

            const res = await fetch(
                this.form.id
                  ? window.routes.productUpdate(this.form.id)
                  : window.routes.productStore,
                {
                    method : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrf,
                        'Accept'      : 'application/json'
                    },
                    body   : fd
                }
            );

            if (res.ok) {
                const { redirect } = await res.json();
                location.href = redirect;
            } else if (res.status === 422) {
                this.errors = (await res.json()).errors;
            }
        }
    }));
    Alpine.data('projectForm', projectForm);
    Alpine.data('instagramForm', instagramForm);

});

Alpine.start();

// Espera a que Alpine inserte el DOM (después de x-data)
document.addEventListener('DOMContentLoaded', () => {
    const nav = document.getElementById('mainNav');
    if (nav) {initNavbarScroll(nav);}
    initCategoryScroll();
});
