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
            return $user->role == 'master';
        });

        Gate::define("accounting_admin", function(User $user){
            return $user->orle == 'accounting_admin';
        });

        Gate::define("purchasing_admin", function(User $user){
            return $user->orle == 'purchasing_admin';
        });
        Gate::define("project_manager", function(User $user){
            return $user->orle == 'project_manager';
        });


        // Other Permission
        Gate::define("self_attendance", function(User $user){
            return $user->allow_self_attendance == 'yes';
        });
    }
}
