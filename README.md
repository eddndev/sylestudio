# Sylestudio

[![Deploy to Production](https://github.com/tu-usuario/sylestudio/actions/workflows/deploy.yml/badge.svg?branch=master)](https://github.com/tu-usuario/sylestudio/actions/workflows/deploy.yml)

> **Your Style. Your Strength. Your Spirit. Your Essence.**

Sylestudio es un estudio creativo multidisciplinario fundado en 2024 en México. Más que una marca, es un movimiento que fusiona moda, fotografía, diseño, música, animación y deporte como estilo de vida.

---

## Tech Stack

| Capa | Tecnología |
|------|------------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Frontend** | Vite, Tailwind CSS 4, Alpine.js |
| **Animaciones** | GSAP |
| **Media** | Spatie Media Library, Intervention Image |
| **Base de datos** | MySQL |
| **Hosting** | Hostgator (cPanel) |

---

## Características

- **Portfolio de Proyectos** - Galería con lazy loading y modal de vista ampliada
- **Imágenes Optimizadas** - Conversiones automáticas a AVIF/WebP con múltiples tamaños
- **Animaciones** - Efecto glitch en títulos, animaciones de scroll con GSAP
- **Newsletter** - Sistema de suscripción con confirmación por email
- **Integración Instagram** - Showcase de posts de la comunidad
- **Panel Admin** - Gestión de proyectos, productos y contenido
- **E-commerce** (WIP) - Tienda con carrito, wishlist y checkout

---

## Requisitos

- PHP 8.2+
- Composer
- Node.js 20+
- MySQL 8.0+

---

## Instalación Local

```bash
# Clonar repositorio
git clone https://github.com/tu-usuario/sylestudio.git
cd sylestudio

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env y migrar
php artisan migrate --seed

# Crear symlink de storage
php artisan storage:link

# Compilar assets
npm run dev

# Iniciar servidor
php artisan serve
```

---

## Desarrollo

```bash
# Servidor de desarrollo con hot reload
npm run dev

# En otra terminal
php artisan serve

# Ejecutar tests
php artisan test
```

---

## Build de Producción

```bash
npm run build
```

---

## Deployment

El proyecto usa **GitHub Actions** para CI/CD. Al hacer push a `master`, automáticamente:

1. Compila assets con Vite
2. Sube `public/build` al servidor
3. Ejecuta `git pull`, `composer install`, migraciones
4. Limpia y regenera cachés
5. Levanta el sitio

### Secretos Requeridos

Configurar en GitHub → Settings → Secrets → Actions:

| Secreto | Descripción |
|---------|-------------|
| `SSH_HOST` | Hostname del servidor |
| `SSH_USERNAME` | Usuario SSH |
| `SSH_PRIVATE_KEY` | Llave privada SSH |
| `PROJECT_PATH` | Ruta absoluta del proyecto |

---

## Estructura del Proyecto

```
sylestudio/
├── app/
│   ├── Http/Controllers/    # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Helpers/             # Funciones helper (get_image)
├── resources/
│   ├── views/
│   │   ├── components/      # Componentes Blade
│   │   ├── layouts/         # Layouts base
│   │   └── pages/           # Vistas de páginas
│   ├── css/                 # Estilos
│   └── js/                  # JavaScript
├── public/
│   └── build/               # Assets compilados (gitignored)
├── routes/
│   └── web.php              # Rutas web
└── .github/
    └── workflows/
        └── deploy.yml       # CI/CD Pipeline
```

---

## Componentes Principales

| Componente | Descripción |
|------------|-------------|
| `<x-responsive-image>` | Imagen con srcset AVIF/WebP |
| `<x-media-image>` | Imagen de galería con fallbacks |
| `<x-project-card>` | Tarjeta de proyecto |
| `<x-category-card>` | Tarjeta de categoría |

---

## Licencia

Todos los derechos reservados © 2024 Sylestudio

---

<p align="center">
  <strong>Hecho con pasión en México</strong>
</p>
