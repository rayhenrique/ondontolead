<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AppReleaseManagementController;
use App\Http\Controllers\Admin\ClinicManagementController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\PlanManagementController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Clinic\AppointmentManagementController;
use App\Http\Controllers\Clinic\ClinicDashboardController;
use App\Http\Controllers\Clinic\ClinicReleaseController;
use App\Http\Controllers\Clinic\ClinicSettingController;
use App\Http\Controllers\PublicClinicBookingController;
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
        if (auth()->user()?->is_superadmin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('app.dashboard');
    })->middleware(['tenant.subscription', 'releases.unread'])->name('dashboard');

    Route::middleware(['tenant.subscription', 'releases.unread'])
        ->prefix('app')
        ->name('app.')
        ->group(function () {
            Route::get('/', ClinicDashboardController::class)->name('dashboard');
            Route::view('/grade', 'clinic.schedule')->name('schedule');
            Route::get('/agendamentos', [AppointmentManagementController::class, 'index'])->name('appointments.index');
            Route::patch('/agendamentos/{appointment}/status', [AppointmentManagementController::class, 'updateStatus'])->name('appointments.status');
            Route::get('/configuracoes', [ClinicSettingController::class, 'edit'])->name('settings.edit');
            Route::put('/configuracoes', [ClinicSettingController::class, 'update'])->name('settings.update');
            Route::get('/novidades', [ClinicReleaseController::class, 'index'])->name('releases.index');
            Route::post('/novidades/{release}/read', [ClinicReleaseController::class, 'markAsRead'])->name('releases.read');
        });

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

Route::get('/{slug}', PublicClinicBookingController::class)
    ->where('slug', '^[a-z0-9]+(?:-[a-z0-9]+)*$')
    ->name('clinic.public');
