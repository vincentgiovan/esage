<?php

namespace App\Providers;

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
        Paginator::useBootstrap();

        if (file_exists($breadcrumbs = base_path('routes/breadcrumb.php'))) {
            require_once $breadcrumbs;
        }

        Gate::define("admin", function(User $user){
            return $user->role == 1;
        });
    }
}
