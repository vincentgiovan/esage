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

        // Main role
        Gate::define("master", function(User $user){
            return $user->role->role_name == 'master';
        });
        Gate::define("accounting admin", function(User $user){
            return $user->role->role_name == 'accounting admin';
        });
        Gate::define("purchasing admin", function(User $user){
            return $user->role->role_name == 'purchasing admin';
        });
        Gate::define("project manager", function(User $user){
            return $user->role->role_name == 'project manager';
        });
        Gate::define("product manager", function(User $user){
            return $user->role->role_name == 'product manager';
        });
        Gate::define("gudang", function(User $user){
            return $user->role->role_name == 'gudang';
        });
        Gate::define("subgudang", function(User $user){
            return $user->role->role_name == 'subgudang';
        });

        // Other Permission
        Gate::define("self_attendance", function(User $user){
            return $user->allow_self_attendance == 'yes';
        });
    }
}
