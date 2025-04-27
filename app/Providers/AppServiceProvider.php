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
        date_default_timezone_set(config('app.timezone')); // ✅ correct
        Carbon::setLocale(config('app.locale')); // optional: for Indonesian day names etc.

        Paginator::useBootstrap();

        if (file_exists($breadcrumbs = base_path('routes/breadcrumb.php'))) {
            require_once $breadcrumbs;
        }

        // Gate for allowing only certain roles
        Gate::define('user-role', function ($user, ...$allowedUserRoles) {
            return in_array($user->role->role_name, $allowedUserRoles);
        });
    }
}
