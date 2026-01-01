<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Redirect::route('visitorsIndex');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/login', 'index')->name('usersIndex');
    Route::post('/login', 'store')->name('usersStore');
});

Route::controller(VisitorController::class)->group(function () {
    Route::get('/visitors', 'index')->name('visitorsIndex');
    Route::get('/visitors/create', 'create')->name('visitorsCreate');
    Route::post('/visitors', 'store')->name('visitorsStore');
    Route::get('/visitors/{visitor}/edit', 'edit')->name('visitorsEdit');
    Route::put('/visitors/{visitor}', 'update')->name('visitorsUpdate');
    Route::delete('/visitors/{visitor}', 'destroy')->name('visitorsDestroy');
});

Route::controller(DepartmentController::class)->group(function () {
    Route::get('departments', 'index')->name('departmentsIndex');
    Route::post('departments', 'store')->name('departmentsStore');
    Route::put('departments/{department}', 'update')->name('departmentsUpdate');
    Route::delete('departments/{department}', 'destroy')->name('departmentsDestroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', []);
});