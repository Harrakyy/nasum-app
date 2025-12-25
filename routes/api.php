<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public data
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{slug}', [PackageController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Midtrans Webhook Routes (PUBLIC, NO AUTH, NO CSRF)
|--------------------------------------------------------------------------
*/

// 🔥 INI URL YANG MASUK KE DASHBOARD MIDTRANS

// Optional (kalau memang kamu pakai)
Route::post('/midtrans/finish', [PaymentController::class, 'handleFinish']);
Route::post('/midtrans/unfinish', [PaymentController::class, 'handleUnfinish']);
Route::post('/midtrans/error', [PaymentController::class, 'handleError']);

// Debug (hapus di production kalau mau)
Route::get('/check-midtrans-config', function () {
    return response()->json([
        'server_key'     => config('services.midtrans.server_key'),
        'client_key'     => config('services.midtrans.client_key'),
        'is_production'  => config('services.midtrans.is_production'),
        'is_sanitized'   => config('services.midtrans.is_sanitized'),
        'is_3ds'         => config('services.midtrans.is_3ds'),
        'merchant_id'    => config('services.midtrans.merchant_id'),
    ]);
});

/*
|--------------------------------------------------------------------------
| Protected API Routes (AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Booking
    Route::apiResource('bookings', BookingController::class)->except(['update', 'destroy']);
    Route::post('/bookings/{id}/upload-payment', [BookingController::class, 'uploadPaymentProof']);

    // Payment
    Route::post('/bookings/{id}/pay', [PaymentController::class, 'createPayment']);
    Route::get('/bookings/{id}/payment-status', [PaymentController::class, 'checkPaymentStatus']);

    // Profile
    Route::put('/profile', function (Request $request) {
        $user = $request->user();

        $user->update($request->validate([
            'name'    => 'sometimes|string|max:255',
            'email'   => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone'   => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user
        ]);
    });

    // Payment history
    Route::get('/payment/history', [PaymentController::class, 'paymentHistory']);

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        Route::apiResource('admin/packages', PackageController::class)->except(['index', 'show']);
        Route::post('/admin/packages/{packageId}/dates', [PackageController::class, 'addDate']);

        Route::put('/admin/bookings/{id}/payment', [BookingController::class, 'updatePayment']);
        Route::delete('/admin/bookings/{id}', [BookingController::class, 'destroy']);

        Route::apiResource('admin/contacts', ContactController::class)->only(['index', 'update', 'destroy']);
        Route::put('/admin/contacts/{id}/status', [ContactController::class, 'updateStatus']);

        Route::get('/admin/payments', [PaymentController::class, 'index']);
        Route::put('/admin/payments/{id}/status', [PaymentController::class, 'updateManualStatus']);

        Route::get('/admin/dashboard/stats', function () {
            return response()->json([
                'total_users'         => \App\Models\User::count(),
                'total_packages'      => \App\Models\Package::count(),
                'total_bookings'      => \App\Models\Booking::count(),
                'pending_payments'    => \App\Models\Booking::where('payment_status', 'pending')->count(),
                'successful_payments' => \App\Models\Booking::where('payment_status', 'settlement')->count(),
                'total_revenue'       => \App\Models\Booking::where('payment_status', 'settlement')->sum('total_price'),
            ]);
        });
    });
});
 