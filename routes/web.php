<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/forms', function () {
        return view('admin.forms');
    })->middleware('permission:access forms')->name('admin.forms');

    Route::get('/tables', function () {
        return view('admin.tables');
    })->middleware('permission:access tables')->name('admin.tables');

    Route::get('/ui-elements', function () {
        return view('admin.ui-elements');
    })->middleware('permission:access ui-elements')->name('admin.ui-elements');
});

 // Group routes that need admin role and authentication
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

require __DIR__.'/auth.php';

