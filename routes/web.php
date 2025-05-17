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
use App\Http\Controllers\PesananController;

// Route::get('/', function () { return view('welcome');});
Route::get('/', [PaketController::class, 'dashboard']);

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
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
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
Route::get('/paket/edit/{id}', [PaketController::class, 'edit'])->middleware(['auth', 'verified'])->name('paket.edit');
Route::put('/paket/{id}', [PaketController::class, 'update'])->middleware(['auth', 'verified'])->name('paket.update');
Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->middleware(['auth', 'verified'])->name('paket.destroy');

Route::get('/keranjang', KeranjangIndex::class)->middleware(['auth', 'verified'])->name('keranjang.index');
Route::post('/keranjang', [KeranjangController::class, 'store'])->middleware(['auth', 'verified'])->name('keranjang.store');
Route::put('/keranjang/{keranjang}', [KeranjangController::class, 'update'])->middleware(['auth', 'verified'])->name('keranjang.update');
Route::delete('/keranjang/{keranjang}', [KeranjangController::class, 'destroy'])->middleware(['auth', 'verified'])->name('keranjang.destroy');
Route::get('/checkout', [KeranjangController::class, 'checkout_index'])->middleware(['auth', 'verified'])->name('checkout.index');
Route::post('/checkout', [KeranjangController::class, 'checkout_store'])->middleware(['auth', 'verified'])->name('checkout.store');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/pesanan/{id}/konfirmasi', [PesananController::class, 'konfirmasi'])->middleware(['auth', 'verified'])->name('pesanan.konfirmasi');
    Route::put('/pesanan/{id}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

});

    Route::get('/admin/pesanan', [PesananController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.pesanan');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::post('/pesanan/{id}/cancel', [PesananController::class, 'cancel'])->middleware(['auth', 'verified'])->name('pesanan.cancel');
});


// Route::controller(JadwalController::class)->group(function(){
//     Route::get('full-calender', 'index');
//     Route::post('full-calender-ajax', 'ajax');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'ajax'])->name('jadwal.ajax');
});


require __DIR__.'/auth.php';