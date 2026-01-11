<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ADMIN
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PenyewaController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\MobilController as AdminMobilController;

// USER
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\RentalUserController;
use App\Http\Controllers\User\MobilController as UserMobilController;
use App\Http\Controllers\User\UlasanController; // Pastikan ini di-import!

/*
|--------------------------------------------------------------------------
| DEFAULT ROUTE
|--------------------------------------------------------------------------
*/
// Cari bagian DEFAULT ROUTE dan ubah menjadi:
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing');

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

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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

    // TRANSAKSI SATU PINTU
    Route::resource('rental', RentalController::class)->only(['index', 'show', 'destroy']);
    
    // Custom Route Konfirmasi & Pengembalian (Pastikan method di controller namanya setKembali)
    Route::post('/rental/{id}/konfirmasi-bayar', [RentalController::class, 'konfirmasiBayar'])->name('rental.konfirmasi');
    Route::post('/rental/{id}/set-kembali', [RentalController::class, 'setKembali'])->name('rental.set_kembali');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::resource('mobil', UserMobilController::class)->only(['index', 'show']);

    // --- PROSES RENTAL ---
    Route::get('/rental/create/{id}', [RentalUserController::class, 'create'])->name('rental.create');
    Route::get('/riwayat', [RentalUserController::class, 'riwayatUser'])->name('riwayat');
    Route::resource('rental', RentalUserController::class)->except(['create']);

    // --- FITUR ULASAN ---
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
    Route::post('/ulasan/store', [UlasanController::class, 'store'])->name('ulasan.store');

    // --- PERBAIKAN DI SINI ---
    // Arahkan ke method 'setKembali' agar sama dengan Admin karena controllernya sama
    Route::post('/rental/kembalikan/{id}', [RentalController::class, 'setKembali'])->name('kembalikan');
});