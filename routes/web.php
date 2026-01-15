<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ADMIN
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PenyewaController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\MobilController as AdminMobilController;
use App\Http\Controllers\Admin\LaporanController;

// USER
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\RentalUserController;
use App\Http\Controllers\User\MobilController as UserMobilController;
use App\Http\Controllers\User\UlasanController;

/*
|--------------------------------------------------------------------------
| DEFAULT ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])
    ->name('landing');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerStore'])->name('register.store');

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginStore'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('mobil', AdminMobilController::class);
    Route::resource('penyewa', PenyewaController::class);
    Route::resource('karyawan', KaryawanController::class);

    // Rental
    Route::resource('rental', RentalController::class)
        ->only(['index', 'show', 'destroy']);

    // ===============================
    // KONFIRMASI PEMBAYARAN RENTAL
    // ===============================
    Route::post('/rental/{id}/konfirmasi', [RentalController::class, 'konfirmasiPembayaran'])
        ->name('rental.konfirmasi');

    Route::post('/invoice/{id}/bayar-kantor', [RentalController::class, 'bayarDiKantor'])
        ->name('invoice.bayar_kantor');

    // ===============================
    // SET KEMBALI (SELESAI)
    // ===============================
    Route::post('/rental/{id}/set-kembali', [RentalController::class, 'setKembali'])
        ->name('rental.set_kembali');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});


/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    // Mobil
    Route::resource('mobil', UserMobilController::class)->only(['index', 'show']);

    /*
    |----------------------------------
    | RENTAL
    |----------------------------------
    | Kita pindahkan create ke atas agar tidak bentrok dengan resource
    */
    Route::get('/rental/create/{id}', [RentalUserController::class, 'create'])
        ->name('rental.create');

    Route::resource('rental', RentalUserController::class)->except(['create']);

    Route::get('/riwayat', [RentalUserController::class, 'riwayatUser'])
        ->name('riwayat');

    /*
    |----------------------------------
    | INVOICE & CETAK (PENAMBAHAN DISINI)
    |----------------------------------
    */
    Route::get('/rental/invoice/{id}', [RentalUserController::class, 'invoiceShow'])
        ->name('rental.invoice');

    // ROUTE BARU UNTUK CETAK STRUK
    Route::get('/rental/invoice/{id}/cetak', [RentalUserController::class, 'cetak'])
        ->name('rental.cetak');

    Route::post('/invoice/{id}/upload-bukti', [RentalUserController::class, 'uploadBukti'])
        ->name('invoice.upload');

    /*
    |----------------------------------
    | ULASAN
    |----------------------------------
    */
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
    Route::post('/ulasan/store', [UlasanController::class, 'store'])->name('ulasan.store');

    /*
    |----------------------------------
    | KEMBALIKAN MOBIL (USER)
    |----------------------------------
    */
    Route::post('/rental/kembalikan/{id}', [RentalUserController::class, 'kembalikan'])
        ->name('kembalikan');
});
