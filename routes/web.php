<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AppReleaseManagementController;
use App\Http\Controllers\Admin\ClinicManagementController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\PlanManagementController;
use App\Http\Controllers\Admin\SystemSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::post('/admin/impersonate/leave', [ImpersonationController::class, 'leave'])->name('admin.impersonate.leave');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['tenant.subscription', 'releases.unread'])->name('dashboard');

    Route::view('/app', 'dashboard')
        ->middleware(['tenant.subscription', 'releases.unread'])
        ->name('app.dashboard');

    Route::middleware(['superadmin', 'releases.unread'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', AdminDashboardController::class)->name('dashboard');

            // Clinics
            Route::get('/clinics', [ClinicManagementController::class, 'index'])->name('clinics.index');
            Route::get('/clinics/create', [ClinicManagementController::class, 'create'])->name('clinics.create');
            Route::post('/clinics', [ClinicManagementController::class, 'store'])->name('clinics.store');
            Route::get('/clinics/{clinic}/edit', [ClinicManagementController::class, 'edit'])->name('clinics.edit');
            Route::put('/clinics/{clinic}', [ClinicManagementController::class, 'update'])->name('clinics.update');
            Route::patch('/clinics/{clinic}/status', [ClinicManagementController::class, 'updateStatus'])->name('clinics.status');
            Route::post('/clinics/{clinic}/impersonate', [ImpersonationController::class, 'impersonate'])->name('clinics.impersonate');

            // Plans
            Route::resource('plans', PlanManagementController::class)->except(['show']);

            // Releases (Changelog)
            Route::resource('releases', AppReleaseManagementController::class)->except(['show']);

            // System Settings
            Route::get('/settings', [SystemSettingController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [SystemSettingController::class, 'update'])->name('settings.update');
        });
});
