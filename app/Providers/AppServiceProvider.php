<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('id');

        Paginator::useBootstrap();

        if (file_exists($breadcrumbs = base_path('routes/breadcrumb.php'))) {
            require_once $breadcrumbs;
        }

        // Gate for allowing only certain roles
        Gate::define('allow', function ($user, ...$allowedRoles) {
            return in_array($user->role->role_name, $allowedRoles);
        });

        // Gate for blocking certain roles
        Gate::define('block', function ($user, ...$blockedRoles) {
            return !in_array($user->role->role_name, $blockedRoles);
        });

        // Deprecated
        Gate::define("self_attendance", function(User $user){
            return $user->allow_self_attendance == 'yes';
        });
    }
}
