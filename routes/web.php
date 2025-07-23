<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    CategoryController,
    DashboardController,
    InstagramMediaController,
    InstagramUploadController,
    ProductController,
    ProjectController as AdminProjectController,
    ProjectUploadController
};
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas y Funcionales
|--------------------------------------------------------------------------
| Estas son las rutas principales que están actualmente activas.
*/
Route::view('/', 'pages.home.home')->name('home');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming-soon');

// Grupo para todo lo relacionado con Proyectos
Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectsController::class, 'index'])->name('index');
    Route::get('/{project:slug}', [ProjectsController::class, 'show'])->name('show');
    Route::get('/{project:slug}/gallery', [ProjectsController::class, 'getGalleryImages'])->name('gallery');
});


/*
|--------------------------------------------------------------------------
| Rutas en Desarrollo (Work in Progress)
|--------------------------------------------------------------------------
| Todas las rutas en este grupo serán redirigidas a la página 'coming-soon'
| gracias al middleware 'feature.wip'.
*/
Route::middleware('feature.wip')->group(function () {
    // Tienda y Productos
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('product.show');
    Route::view('/cart', 'pages.cart')->name('cart');
    
    // Área de Usuario Autenticado
    Route::middleware('auth')->group(function () {
        Route::view('/wishlist', 'pages.wishlist')->name('wishlist');
        
        Route::middleware('verified')->group(function() {
            Route::view('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Panel de Administración (Funcional)
|--------------------------------------------------------------------------
| Estas rutas no se ven afectadas y permanecen activas para los administradores.
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Endpoint de carga para FilePond de Proyectos (necesita estar aquí por la URL)
        Route::get('/projects/uploads/load/{filename}', [ProjectUploadController::class, 'load'])->name('projects.uploads.load');

        // Productos y Categorías
        Route::resource('products', ProductController::class)->middleware('can:manage-products');
        Route::resource('categories', CategoryController::class)->except(['create','show','edit'])->middleware('can:manage-categories');
        Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder')->middleware('can:manage-products');

        // Proyectos
        Route::middleware('can:manage-projects')->group(function () {
            Route::post('projects/uploads', [ProjectUploadController::class, 'store'])->name('projects.uploads.store');
            Route::delete('projects/uploads', [ProjectUploadController::class, 'destroy'])->name('projects.uploads.destroy');
            Route::post('projects/{project}/gallery/reorder', [AdminProjectController::class, 'reorderGallery'])->name('projects.gallery.reorder');
            Route::resource('projects', AdminProjectController::class);
        });

        // Instagram
        Route::middleware('can:manage-instagram')->group(function () {
            Route::post('instagram/uploads', [InstagramUploadController::class, 'store'])->name('instagram.uploads.store');
            Route::delete('instagram/uploads', [InstagramUploadController::class, 'destroy'])->name('instagram.uploads.destroy');
            Route::resource('instagram', InstagramMediaController::class);
        });
    });

Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])
    ->name('newsletter.store');

Route::get('/newsletter/pending', [NewsletterSubscriptionController::class, 'pending'])
    ->name('newsletter.pending');

Route::get('/newsletter/confirm/{token}', [NewsletterSubscriptionController::class, 'confirm'])
    ->middleware('signed')
    ->name('newsletter.confirm');

Route::get('/newsletter/unsubscribe/{token}', [NewsletterSubscriptionController::class, 'unsubscribe'])
    ->middleware('signed')
    ->name('newsletter.unsubscribe');
/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
