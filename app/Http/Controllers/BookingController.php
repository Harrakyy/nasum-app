<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\PackageDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Midtrans
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    /**
     * SIMPAN BOOKING (AWAL)
     */
    public function webStore(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'room_type' => 'required|in:double,triple,quad',
            'tanggal_keberangkatan' => 'required|date',
            'nama_jamaah' => 'required|string|max:255',
            'email' => 'required|email',
            'nomor_telepon' => 'required|string',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string',
            'nama_darurat' => 'required|string',
            'nomor_darurat' => 'required|string',
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);

        $packageDate = PackageDate::firstOrCreate(
            [
                'package_id' => $package->id,
                'departure_date' => $request->tanggal_keberangkatan,
            ],
            [
                'display_date' => Carbon::parse($request->tanggal_keberangkatan)
                    ->translatedFormat('d F Y'),
                'available_slots' => 20,
                'is_available' => true,
            ]
        );

        if ($packageDate->available_slots <= 0) {
            return back()->with('error', 'Kuota penuh.');
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_date_id' => $packageDate->id,
            'room_type' => $request->room_type,
            'total_price' => $package->getPriceByRoomType($request->room_type),
            'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
            'jumlah_jamaah' => 1,
            'customer_name' => $request->nama_jamaah,
            'customer_email' => $request->email,
            'customer_phone' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'nama_darurat' => $request->nama_darurat,
            'nomor_darurat' => $request->nomor_darurat,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $packageDate->decrement('available_slots');

        return redirect()
            ->route('booking.confirmation', $booking->id)
            ->with('success', 'Pemesanan berhasil.');
    }

    /**
     * HALAMAN KONFIRMASI + SNAP MIDTRANS
     */
    public function confirmation(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['package', 'packageDate']);

        // 🔐 SET CONFIG MIDTRANS (WAJIB, INI YANG BIKIN 500 HILANG)
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 🔥 GENERATE SNAP TOKEN SEKALI
        if (!$booking->snap_token) {
            $params = [
                'transaction_details' => [
                    'order_id' => $booking->booking_code, // JANGAN DITAMBAH TIME
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $booking->customer_name,
                    'email' => $booking->customer_email,
                    'phone' => $booking->customer_phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $booking->update([
                'snap_token' => $snapToken,
                'payment_status' => 'pending',
            ]);
        }

        // ⚠️ KIRIM SEMUA VARIABEL YANG DIPAKAI VIEW
        return view('konfirmasi_pemesanan', [
            'booking' => $booking,
            'snapToken' => $booking->snap_token,
            'client_key' => config('services.midtrans.client_key'),
        ]);
    }

    /**
     * UPLOAD BUKTI BAYAR (NON-MIDTRANS)
     */
    public function webUploadPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_proof' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'payment_method' => 'required|string',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $booking->update([
            'payment_proof' => $path,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return redirect()
            ->route('my.umrah')
            ->with('success', 'Bukti pembayaran diunggah.');
    }

    /**
     * ADMIN – VERIFIKASI MANUAL
     */
    public function adminVerifyPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,pending,settlement,failed',
        ]);

        $booking->update([
            'payment_status' => $request->payment_status,
            'status' => $request->payment_status === 'settlement'
                ? 'confirmed'
                : 'pending',
            'payment_date' => $request->payment_status === 'settlement'
                ? now()
                : null,
        ]);

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    /**
     * ADMIN – HAPUS BOOKING
     */
    public function adminDelete(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking dihapus.');
    }

    public function detail(Booking $booking)
{
    // keamanan: pastikan booking milik user
    if ($booking->user_id !== auth()->id()) {
        abort(403);
    }

    return view('booking-detail', compact('booking'));
}

}
