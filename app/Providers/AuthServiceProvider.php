<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\GroupPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Company::class => CompanyPolicy::class,
        Group::class    => GroupPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

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

        Sanctum::ignoreMigrations();
    }
}
