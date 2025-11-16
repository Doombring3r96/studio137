<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Service::class => \App\Policies\ServicePolicy::class,
        \App\Models\Brief::class => \App\Policies\BriefPolicy::class,
        \App\Models\Logo::class => \App\Policies\LogoPolicy::class,
        \App\Models\PublicationCalendar::class => \App\Policies\PublicationCalendarPolicy::class,
        \App\Models\Artwork::class => \App\Policies\ArtworkPolicy::class,
        \App\Models\Assignment::class => \App\Policies\AssignmentPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gates adicionales para permisos específicos
        Gate::define('access-dashboard', function ($user) {
            return $user->isStaff();
        });

        Gate::define('manage-payments', function ($user) {
            return $user->isCEO() || $user->isDeveloper();
        });

        Gate::define('manage-salaries', function ($user) {
            return $user->isCEO() || $user->isDeveloper();
        });

        Gate::define('view-reports', function ($user) {
            return $user->isStaff() && !$user->isCliente();
        });
    }
}