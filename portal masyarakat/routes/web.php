<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])
        ->middleware('throttle:6,1')
        ->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::get('/user/dashboard', [ComplaintController::class, 'userDashboard'])
        ->middleware('role:user')
        ->name('user.dashboard');

    Route::get('/user/complaints/create', [ComplaintController::class, 'create'])
        ->middleware('role:user')
        ->name('user.complaints.create');

    Route::get('/user/complaints', [ComplaintController::class, 'index'])
        ->middleware('role:user')
        ->name('user.complaints.index');

    Route::post('/complaints', [ComplaintController::class, 'store'])
        ->middleware('role:user')
        ->name('complaints.store');

    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])
        ->middleware('role:user')
        ->name('complaints.destroy');

    Route::get('/admin/dashboard', [ComplaintController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.users.index');

    Route::patch('/admin/complaints/{complaint}', [ComplaintController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.complaints.update');

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});
