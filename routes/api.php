<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

// Public API routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{slug}', [PackageController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);

// Midtrans notification routes
Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification']);
Route::post('/midtrans/finish', [PaymentController::class, 'handleFinish']);
Route::post('/midtrans/unfinish', [PaymentController::class, 'handleUnfinish']);
Route::post('/midtrans/error', [PaymentController::class, 'handleError']);

// Midtrans config check
Route::get('/check-midtrans-config', function() {
    return response()->json([
        'server_key' => config('services.midtrans.server_key'),
        'client_key' => config('services.midtrans.client_key'),
        'is_production' => config('services.midtrans.is_production'),
        'is_sanitized' => config('services.midtrans.is_sanitized'),
        'is_3ds' => config('services.midtrans.is_3ds'),
        'merchant_id' => config('services.midtrans.merchant_id'),
    ]);
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Booking routes
    Route::apiResource('bookings', BookingController::class)->except(['update', 'destroy']);
    Route::post('/bookings/{id}/upload-payment', [BookingController::class, 'uploadPaymentProof']);
    
    // Payment routes
    Route::post('/bookings/{id}/pay', [PaymentController::class, 'createPayment']);
    Route::get('/bookings/{id}/payment-status', [PaymentController::class, 'checkPaymentStatus']);
    
    // Profile update
    Route::put('/profile', function (Request $request) {
        $user = $request->user();
        $user->update($request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]));
        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    });

    // Payment history
    Route::get('/payment/history', [PaymentController::class, 'paymentHistory']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        // Package management
        Route::apiResource('admin/packages', PackageController::class)->except(['index', 'show']);
        Route::post('/admin/packages/{packageId}/dates', [PackageController::class, 'addDate']);
        
        // Booking management
        Route::put('/admin/bookings/{id}/payment', [BookingController::class, 'updatePayment']);
        Route::delete('/admin/bookings/{id}', [BookingController::class, 'destroy']);
        
        // Contact management
        Route::apiResource('admin/contacts', ContactController::class)->only(['index', 'update', 'destroy']);
        Route::put('/admin/contacts/{id}/status', [ContactController::class, 'updateStatus']);
        
        // Payment management
        Route::get('/admin/payments', [PaymentController::class, 'index']);
        Route::put('/admin/payments/{id}/status', [PaymentController::class, 'updateManualStatus']);
        
        // Dashboard stats
        Route::get('/admin/dashboard/stats', function () {
            $totalUsers = \App\Models\User::count();
            $totalPackages = \App\Models\Package::count();
            $totalBookings = \App\Models\Booking::count();
            $pendingPayments = \App\Models\Booking::where('payment_status', 'pending')->count();
            $successfulPayments = \App\Models\Booking::where('payment_status', 'paid')->count();
            $totalRevenue = \App\Models\Booking::where('payment_status', 'paid')->sum('total_amount');
            
            return response()->json([
                'total_users' => $totalUsers,
                'total_packages' => $totalPackages,
                'total_bookings' => $totalBookings,
                'pending_payments' => $pendingPayments,
                'successful_payments' => $successfulPayments,
                'total_revenue' => $totalRevenue,
                'bookings_by_package' => \App\Models\Booking::groupBy('package_id')
                    ->selectRaw('package_id, count(*) as total')
                    ->with('package')
                    ->get(),
                'recent_payments' => \App\Models\Booking::with(['user', 'package'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
            ]);
        });
    });
});