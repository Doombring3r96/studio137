<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
// ... otros controladores

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
});

require __DIR__.'/auth.php';