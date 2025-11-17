<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mapear tipos de entidad para relaciones polimórficas
        Relation::morphMap([
            'service' => 'App\Models\Service',
            'assignment' => 'App\Models\Assignment',
            'brief' => 'App\Models\Brief',
            'logo' => 'App\Models\Logo',
            'publication_calendar' => 'App\Models\PublicationCalendar',
            'artwork' => 'App\Models\Artwork',
            'payment' => 'App\Models\Payment',
            'salary' => 'App\Models\Salary',
            'user' => 'App\Models\User',
            'role' => 'App\Models\Role',
            'manual' => 'App\Models\Manual',
            'report' => 'App\Models\Report',
            'notification' => 'App\Models\Notification',
            'audit' => 'App\Models\Audit',
        ]);

        // Registrar observers - FORMA CORREGIDA
        $this->registerObservers();
    }

    /**
     * Registrar los observers de los modelos
     */
    protected function registerObservers(): void
    {
        // Verificar que las clases existen antes de registrar
        if (class_exists(\App\Models\Service::class) && class_exists(\App\Observers\ServiceObserver::class)) {
            \App\Models\Service::observe(\App\Observers\ServiceObserver::class);
        }
        
        if (class_exists(\App\Models\Assignment::class) && class_exists(\App\Observers\AssignmentObserver::class)) {
            \App\Models\Assignment::observe(\App\Observers\AssignmentObserver::class);
        }
        
        if (class_exists(\App\Models\PublicationCalendar::class) && class_exists(\App\Observers\PublicationCalendarObserver::class)) {
            \App\Models\PublicationCalendar::observe(\App\Observers\PublicationCalendarObserver::class);
        }
        
        if (class_exists(\App\Models\Logo::class) && class_exists(\App\Observers\LogoObserver::class)) {
            \App\Models\Logo::observe(\App\Observers\LogoObserver::class);
        }
        
        if (class_exists(\App\Models\Brief::class) && class_exists(\App\Observers\BriefObserver::class)) {
            \App\Models\Brief::observe(\App\Observers\BriefObserver::class);
        }
        
        if (class_exists(\App\Models\Payment::class) && class_exists(\App\Observers\PaymentObserver::class)) {
            \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
        }
        
        if (class_exists(\App\Models\Salary::class) && class_exists(\App\Observers\SalaryObserver::class)) {
            \App\Models\Salary::observe(\App\Observers\SalaryObserver::class);
        }
    }
}