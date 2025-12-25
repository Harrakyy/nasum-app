<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PackageController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/booking/{booking}', [BookingController::class, 'detail'])
    ->name('booking.detail');
Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');
Route::get('/daftar-umroh', [PageController::class, 'packages'])->name('packages');
Route::get('/hubungi-kami', [PageController::class, 'contact'])->name('contact');

Route::get('/paket/umroh-dubai', [PageController::class, 'packageDubai'])->name('package.dubai');
Route::get('/paket/umroh-turki', [PageController::class, 'packageTurki'])->name('package.turki');
Route::get('/paket/umroh-reguler', [PageController::class, 'packageReguler'])->name('package.reguler');

/*
|--------------------------------------------------------------------------
| User Login / Register (Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.process');

    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

/*
|--------------------------------------------------------------------------
| Admin Login (Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.process');
});

/*
|--------------------------------------------------------------------------
| Booking (public POST)
|--------------------------------------------------------------------------
*/
Route::post('/pesan', [BookingController::class, 'webStore'])->name('booking.store');

/*
|--------------------------------------------------------------------------
| User Area (auth only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

    Route::get('/umroh-saya', [PageController::class, 'myUmrah'])->name('my.umrah');
    Route::get('/profil', [PageController::class, 'profile'])->name('profile');
    Route::put('/profil/update', [PageController::class, 'updateProfile'])->name('profile.update');

    Route::get('/form-pemesanan', [PageController::class, 'bookingForm'])->name('booking.form');
    Route::get('/konfirmasi-pemesanan/{booking}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

    Route::get('/booking/{booking}/pay', [PaymentController::class, 'pay'])->name('booking.pay');
    Route::get('/pembayaran/{booking}', [PaymentController::class, 'pay'])->name('payment.page');

    Route::post('/upload-bukti-bayar', [BookingController::class, 'webUploadPayment'])->name('booking.upload');
});

/*
|--------------------------------------------------------------------------
| Admin Area (auth + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [PageController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/kelola-paket', [PageController::class, 'adminManagePackages'])->name('manage.packages');
        Route::get('/verifikasi-pembayaran', [PageController::class, 'adminVerifyPayments'])->name('verify.payments');
        Route::delete('/booking/{id}', [PageController::class, 'deleteBooking'])->name('booking.delete');

        // 🔥 FIX UTAMA: PAKAI PackageController, BUKAN daftarumroh
        Route::post('/paket', [PackageController::class, 'webStore'])->name('packages.store');
        Route::put('/paket/{id}', [PackageController::class, 'webUpdate'])->name('packages.update');
        Route::delete('/paket/{id}', [PackageController::class, 'webDestroy'])->name('packages.destroy');
        Route::post('/paket/tanggal', [PackageController::class, 'webAddDate'])->name('packages.addDate');
    });

/*
|--------------------------------------------------------------------------
| Contact Form
|--------------------------------------------------------------------------
*/
Route::post('/kontak', [ContactController::class, 'webStore'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| ❌ JANGAN taruh webhook Midtrans di web.php
|    (HARUS di routes/api.php)
|--------------------------------------------------------------------------
*/
