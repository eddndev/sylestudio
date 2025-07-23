<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /*
         |──────────────────────────────
         |  Gates de alto nivel
         |──────────────────────────────
         | Cambia la lógica si usas roles más complejos:
         |   – tabla roles / role_user
         |   – Spatie\Permission, etc.
         */

        // Acceso global al panel de administración
        Gate::define('admin-access', fn (User $user) => $user->is_admin);

        // Permisos específicos dentro del panel
        Gate::define('manage-products', fn (User $user) => $user->is_admin);
        Gate::define('manage-events',   fn (User $user) => $user->is_admin);
        Gate::define('manage-categories', fn (User $user) => $user->is_admin);
        Gate::define('manage-projects', fn (User $user) => $user->is_admin);
        Gate::define('manage-instagram', fn (User $user) => $user->is_admin);
    }
}
