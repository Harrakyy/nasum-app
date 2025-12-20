<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');
Route::get('/daftar-umroh', [PageController::class, 'packages'])->name('packages');
Route::get('/hubungi-kami', [PageController::class, 'contact'])->name('contact');

Route::get('/paket/umroh-dubai', [PageController::class, 'packageDubai'])->name('package.dubai');
Route::get('/paket/umroh-turki', [PageController::class, 'packageTurki'])->name('package.turki');
Route::get('/paket/umroh-reguler', [PageController::class, 'packageReguler'])->name('package.reguler');

/*
|--------------------------------------------------------------------------
| Auth (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.process');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

/*
|--------------------------------------------------------------------------
| User Area (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'weblogout'])->name('logout');

    Route::get('/umroh-saya', [PageController::class, 'myUmrah'])->name('my.umrah');
    Route::get('/profil', [PageController::class, 'profile'])->name('profile');
    Route::put('/profil/update', [PageController::class, 'updateProfile'])->name('profile.update');

    Route::get('/form-pemesanan', [PageController::class, 'bookingForm'])->name('booking.form');
    

    Route::post('/pesan', [BookingController::class, 'webStore'])->name('booking.store');
    Route::post('/upload-bukti-bayar', [BookingController::class, 'webUploadPayment'])->name('booking.upload');
   Route::get('/konfirmasi-pemesanan', [BookingController::class, 'confirmation'])
    ->name('booking.confirmation');
Route::post('/konfirmasi-pemesanan', [BookingController::class, 'processConfirmation'])
    ->name('booking.confirmation'); // untuk menerima POST form
});

/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/beranda', [PageController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/kelola-paket', [PageController::class, 'adminManagePackages'])->name('manage.packages');
        Route::get('/verifikasi-pembayaran', [PageController::class, 'adminVerifyPayments'])->name('verify.payments');
    });

/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/
Route::post('/kontak', [ContactController::class, 'webStore'])->name('contact.store');
// Test route untuk check midtrans config
Route::get('/check-midtrans-config', function() {
    return response()->json([
        'server_key' => config('services.midtrans.server_key'),
        'client_key' => config('services.midtrans.client_key'),
        'is_production' => config('services.midtrans.is_production'),
        'merchant_id' => config('services.midtrans.merchant_id'),
        'message' => 'Midtrans configuration loaded successfully',
        'app_name' => config('app.name'),
        'url' => config('app.url')
    ]);
});