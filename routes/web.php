<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['tenant.subscription', 'releases.unread'])->name('dashboard');

    Route::view('/admin', 'dashboard')
        ->middleware(['superadmin', 'releases.unread'])
        ->name('admin.dashboard');

    Route::view('/app', 'dashboard')
        ->middleware(['tenant.subscription', 'releases.unread'])
        ->name('app.dashboard');
});
