<?php

use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', []);
})->name('home');

Route::controller(VisitorController::class)->group(function () {
    Route::get('/visitors', 'index')->name('visitorsIndex');
    Route::get('/visitors/create', 'create')->name('visitorsCreate');
    Route::post('/visitors', 'store')->name('visitorsStore');
    Route::get('/visitors/{visitor}/edit', 'edit')->name('visitorsEdit');
    Route::put('/visitors/{visitor}', 'update')->name('visitorsUpdate');
    Route::delete('/visitors/{visitor}', 'destroy')->name('visitorsDestroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', []);
});

Route::get('/departments', function () {
    return Inertia::render('Departments', []);
});