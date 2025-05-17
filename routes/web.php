<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\KeranjangController;
use App\Livewire\KeranjangIndex;
use App\Http\Controllers\JadwalController;

// Route utama
Route::get('/', [PaketController::class, 'dashboard']);

// Route untuk semua pengguna terautentikasi
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect dashboard berdasarkan role
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Route profile yang bisa diakses semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route khusus customer
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
    
    // Route lain untuk customer bisa ditambahkan di sini
});

// Route khusus admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/forms', function () {
        return view('admin.forms');
    })->middleware('permission:access forms')->name('forms');

    Route::get('/tables', function () {
        return view('admin.tables');
    })->middleware('permission:access tables')->name('tables');
    
    Route::get('/ui-elements', function () {
        return view('admin.ui-elements');
    })->middleware('permission:access ui-elements')->name('ui-elements');

    Route::get('/conf', function () {
        return view('admin.configuration');
    })->middleware('permission:access configuration')->name('configuration');
    
    // Resource routes untuk admin
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

// Route untuk paket
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
    Route::get('/paket/tambah', [PaketController::class, 'create'])->name('paket.create');
    Route::post('/paket/tambah', [PaketController::class, 'store'])->name('paket.store');
    Route::get('/paket/edit/{id}', [PaketController::class, 'edit'])->name('paket.edit');
    Route::put('/paket/{id}', [PaketController::class, 'update'])->name('paket.update');
    Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');
});

// Route untuk keranjang
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/keranjang', KeranjangIndex::class)->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::put('/keranjang/{keranjang}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{keranjang}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::get('/checkout', [KeranjangController::class, 'checkout_index'])->name('checkout.index');
    Route::post('/checkout', [KeranjangController::class, 'checkout_store'])->name('checkout.store');
});

// Route untuk jadwal
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'ajax'])->name('jadwal.ajax');
});

require __DIR__.'/auth.php';