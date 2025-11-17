<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Controladores generales
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BriefController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicationCalendarController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\ReportController;

// Controladores para Cliente
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\ServiceController as ClientServiceController;
use App\Http\Controllers\Client\ReportController as ClientReportController;
use App\Http\Controllers\Client\PaymentController as ClientPaymentController;
// Controladores para CM
use App\Http\Controllers\Cm\DashboardController as CmDashboardController;
use App\Http\Controllers\Cm\CalendarController as CmCalendarController;
use App\Http\Controllers\Cm\ArtworkController as CmArtworkController;
use App\Http\Controllers\Cm\SalaryController as CmSalaryController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'can:access-dashboard'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para todos los staff
    Route::middleware(['can:access-dashboard'])->group(function () {
        Route::resource('services', ServiceController::class);
        Route::resource('briefs', BriefController::class);
        Route::resource('assignments', AssignmentController::class);
        
        // Rutas específicas con permisos
        Route::post('/services/{service}/change-status', [ServiceController::class, 'changeStatus'])
            ->name('services.change-status');
            
        Route::get('/my-assignments', [AssignmentController::class, 'myAssignments'])
            ->name('assignments.my');
            
        Route::post('/assignments/{assignment}/complete', [AssignmentController::class, 'complete'])
            ->name('assignments.complete');
    });

    // Rutas solo para CEO y Developer
    Route::middleware(['role:ceo,developer'])->group(function () {
        Route::resource('salaries', SalaryController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('users', UserController::class);
        
        Route::post('/salaries/{salary}/mark-paid', [SalaryController::class, 'markAsPaid'])
            ->name('salaries.mark-paid');
    });

    // Rutas para directores
    Route::middleware(['role:director_marca,director_creativo,ceo,developer'])->group(function () {
        Route::resource('publication-calendars', PublicationCalendarController::class);
        Route::resource('logos', LogoController::class);
        
        Route::post('/publication-calendars/{publicationCalendar}/add-correction', 
            [PublicationCalendarController::class, 'addCorrection'])
            ->name('publication-calendars.add-correction');
    });

    // Rutas para reportes (solo staff)
    Route::middleware(['can:view-reports'])->group(function () {
        Route::get('/reports/performance', [ReportController::class, 'performance'])
            ->name('reports.performance');
        Route::get('/reports/assignments', [ReportController::class, 'assignments'])
            ->name('reports.assignments');
    });

    // =================================================================
    // RUTAS ESPECÍFICAS PARA CLIENTE
    // =================================================================
    Route::middleware(['auth', 'can:access-dashboard'])->prefix('client')->name('client.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        
        // Servicios
        Route::get('/services', [ClientServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service}', [ClientServiceController::class, 'show'])->name('services.show');
        Route::get('/services/{service}/brief', [ClientServiceController::class, 'showBriefForm'])->name('services.brief.create');
        Route::post('/services/{service}/brief', [ClientServiceController::class, 'storeBrief'])->name('services.brief.store');
        Route::get('/services/{service}/logos', [ClientServiceController::class, 'showLogos'])->name('services.logos');
        Route::get('/services/{service}/calendars', [ClientServiceController::class, 'showCalendars'])->name('services.calendars');
        
        // Acciones para logos
        Route::post('/services/logos/{logo}/approve', [ClientServiceController::class, 'approveLogo'])->name('services.logos.approve');
        Route::post('/services/logos/{logo}/reject', [ClientServiceController::class, 'rejectLogo'])->name('services.logos.reject');
        
        // Acciones para calendarios
        Route::post('/services/calendars/{calendar}/approve', [ClientServiceController::class, 'approveCalendar'])->name('services.calendars.approve');
        Route::post('/services/calendars/{calendar}/correct', [ClientServiceController::class, 'correctCalendar'])->name('services.calendars.correct');
        
        // Informes
        Route::get('/reports', [ClientReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [ClientReportController::class, 'show'])->name('reports.show');
        
        // Pagos
        Route::get('/payments', [ClientPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}/pay', [ClientPaymentController::class, 'showPaymentForm'])->name('payments.pay');
        Route::post('/payments/{payment}/pay', [ClientPaymentController::class, 'processPayment'])->name('payments.process');
    });
    // =================================================================
    // RUTAS ESPECÍFICAS PARA CM
    // =================================================================
    // Rutas para Community Manager
    Route::middleware(['auth', 'can:access-dashboard'])->prefix('cm')->name('cm.')->group(function () {
    // Dashboard
        Route::get('/dashboard', [Cm\DashboardController::class, 'index'])->name('dashboard');
    
        // Calendarios de Publicación
        Route::get('/calendars', [Cm\CalendarController::class, 'index'])->name('calendars.index');
        Route::get('/calendars/create', [Cm\CalendarController::class, 'create'])->name('calendars.create');
        Route::post('/calendars', [Cm\CalendarController::class, 'store'])->name('calendars.store');
        Route::get('/calendars/{calendar}', [Cm\CalendarController::class, 'show'])->name('calendars.show');
        Route::get('/calendars/{calendar}/edit', [Cm\CalendarController::class, 'edit'])->name('calendars.edit');
        Route::put('/calendars/{calendar}', [Cm\CalendarController::class, 'update'])->name('calendars.update');
        Route::delete('/calendars/{calendar}', [Cm\CalendarController::class, 'destroy'])->name('calendars.destroy');
    
        // Artes para Calendarios
        Route::get('/calendars/{calendar}/artworks', [Cm\ArtworkController::class, 'index'])->name('calendars.artworks.index');
        Route::get('/calendars/{calendar}/artworks/create', [Cm\ArtworkController::class, 'create'])->name('calendars.artworks.create');
        Route::post('/calendars/{calendar}/artworks', [Cm\ArtworkController::class, 'store'])->name('calendars.artworks.store');
        Route::get('/calendars/{calendar}/artworks/{artwork}/edit', [Cm\ArtworkController::class, 'edit'])->name('calendars.artworks.edit');
        Route::put('/calendars/{calendar}/artworks/{artwork}', [Cm\ArtworkController::class, 'update'])->name('calendars.artworks.update');
        Route::delete('/calendars/{calendar}/artworks/{artwork}', [Cm\ArtworkController::class, 'destroy'])->name('calendars.artworks.destroy');
        
        // Sueldos
        Route::get('/salaries', [Cm\SalaryController::class, 'index'])->name('salaries.index');
        Route::get('/salaries/{salary}', [Cm\SalaryController::class, 'show'])->name('salaries.show');
        
        // Acciones específicas
        Route::post('/calendars/{calendar}/submit', [Cm\CalendarController::class, 'submitForApproval'])->name('calendars.submit');
        Route::post('/calendars/{calendar}/mark-completed', [Cm\CalendarController::class, 'markAsCompleted'])->name('calendars.mark-completed');
    });
});

require __DIR__.'/auth.php';