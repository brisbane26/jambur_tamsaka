<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\PaketController;

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

     Route::get('/conf', function () {
               return view('admin.configuration');
           })->middleware('permission:access configuration')->name('admin.configuration');
    });
    
    
    
 // Group routes that need admin role and authentication
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

Route::get('/paket', [PaketController::class, 'index'])->middleware(['auth', 'verified'])->name('paket.index');
Route::get('/paket/tambah', [PaketController::class, 'create'])->middleware(['auth', 'verified'])->name('paket.create');
Route::post('/paket/tambah', [PaketController::class, 'store'])->middleware(['auth', 'verified'])->name('paket.store');
Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->middleware(['auth', 'verified'])->name('paket.destroy');

require __DIR__.'/auth.php';

