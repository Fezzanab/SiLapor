<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SawController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pelapor Routes
    Route::middleware('role:pelapor')->prefix('pelapor')->name('pelapor.')->group(function () {
        Route::get('/dashboard', [PelaporController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports/create', [PelaporController::class, 'create'])->name('reports.create');
        Route::post('/reports', [PelaporController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [PelaporController::class, 'show'])->name('reports.show');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/gis', [AdminController::class, 'gis'])->name('gis');
        Route::get('/reports/{report}', [AdminController::class, 'show'])->name('reports.show');
        Route::post('/reports/{report}/verify', [AdminController::class, 'verify'])->name('reports.verify');
        Route::post('/reports/{report}/assign', [AdminController::class, 'assign'])->name('reports.assign');
        Route::get('/audit', [AdminController::class, 'audit'])->name('audit');

        // SAW Routes
        Route::get('/saw/criteria', [SawController::class, 'criteria'])->name('saw.criteria');
        Route::put('/saw/criteria/{criterion}', [SawController::class, 'updateCriterion'])->name('saw.criteria.update');
        Route::get('/saw/ranking', [SawController::class, 'ranking'])->name('saw.ranking');
        Route::post('/saw/calculate', [SawController::class, 'calculate'])->name('saw.calculate');
    });

    // Staff Routes
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        Route::get('/tasks/{task}', [StaffController::class, 'show'])->name('tasks.show');
        Route::post('/tasks/{task}/update', [StaffController::class, 'updateStatus'])->name('tasks.update');
    });
});
