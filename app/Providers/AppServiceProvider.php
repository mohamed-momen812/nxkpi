<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\GroupPolicy;
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
        Gate::define('update-company', [CompanyPolicy::class, 'update']);
        Gate::define('update-group', [GroupPolicy::class, 'update']);
        Gate::define('manage_users', function(User $user) {
            return $user->hasRole(['Owner', 'Manager']) ;
        });
        Gate::define('manage_users', function(User $user) {
            return $user->hasRole(['Owner', 'Manager']) ;
        });
        Gate::define('access-assigned-kpis', function(User $user) {
            return $user->hasRole(['Owner', 'User']) ;
        });
        Gate::define('create-kpis', function(User $user) {
            return $user->hasRole(['Owner', 'Manager']) ;
        });
        Gate::define('edit-kpis', function(User $user) {
            return $user->hasRole(['Owner', 'Manager']) ;
        });
        Gate::define('view-kpis', function(User $user) {
            return $user->hasRole(['Owner', 'Director']) ;
        });
    }
}
