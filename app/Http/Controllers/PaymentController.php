<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class PaymentController extends Controller
{
        public function __construct()
        {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }

   public function pay(Booking $booking)
{
    // JANGAN generate ulang kalau sudah ada
    if (!$booking->snap_token) {

        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code, // STABIL
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $booking->update([
            'snap_token' => $snapToken,
            'payment_status' => 'pending',
        ]);
    }

    return view('payment', [
        'booking' => $booking->fresh(),
        'client_key' => config('services.midtrans.client_key'),
        'snapToken' => $booking->snap_token,
    ]);
}



public function handleNotification(Request $request)
{
    $booking = Booking::where('booking_code', $request->order_id)->firstOrFail();

    $status = $request->transaction_status;
    $fraud  = $request->fraud_status;

    $paymentMethod = $request->payment_type ?? null;

    if ($paymentMethod === 'bank_transfer' && isset($request->va_numbers[0]['bank'])) {
        $paymentMethod = $request->va_numbers[0]['bank'];
    }

    if (
        $status === 'settlement' ||
        ($status === 'capture' && $fraud === 'accept')
    ) {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',

            // ⬇️ INI AKAN TERISI WALAU STATUS SUDAH PAID
            'payment_method' => $booking->payment_method ?? $paymentMethod,
            'payment_date'   => $booking->payment_date
                ?? ($request->settlement_time
                    ? \Carbon\Carbon::parse($request->settlement_time)
                    : now()),
            'payment_proof'  => $booking->payment_proof ?? $request->transaction_id,
        ]);
    }

    return response()->json(['ok' => true]);
}





}
