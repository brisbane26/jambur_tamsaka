<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
use App\Livewire\ShowPesanans;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

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



Route::get('/pesanan', ShowPesanans::class)->name('pesanan.index');

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

Route::middleware(['auth', 'verified'])->group(function () {
    // Route untuk pesanan
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [PesananController::class, 'index'])->name('index');
        Route::get('/{pesanan}', [PesananController::class, 'show'])->name('show');
        
        // Hanya admin yang bisa update status
        Route::middleware(['role:admin'])->group(function () {
            Route::put('/{pesanan}/update-status', [PesananController::class, 'updateStatus'])->name('updateStatus');
        });
        
        // Hanya customer yang bisa update bukti transfer
        Route::middleware(['role:customer'])->group(function () {
            Route::put('/{pesanan}/update-bukti', [PesananController::class, 'updateBukti'])->name('updateBukti');
        });
        
        // Baik admin maupun customer bisa cancel (dengan kondisi berbeda)
        Route::post('/{pesanan}/cancel', [PesananController::class, 'cancel'])->name('cancel');
        Route::get('/pesanan/history', [PesananController::class, 'history'])->name('history');

    });
});

Route::get('/laporan/{pesanan}', [PesananController::class, 'detail'])->name('laporan.detail');

// Route::controller(JadwalController::class)->group(function(){
//     Route::get('full-calender', 'index');
//     Route::post('full-calender-ajax', 'ajax');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'ajax'])->name('jadwal.ajax');
});


Route::get('/contact-us', function () {
    $title = "Contact Us"; // Atur nilai variabel title
    return view('contact.index', compact('title')); // Kirim variabel ke view
});

Route::get('/laporan', [PesananController::class, 'laporan'])->name('laporan.index');
Route::get('/paket/search', [PaketController::class, 'search'])->name('paket.search');

require __DIR__.'/auth.php';