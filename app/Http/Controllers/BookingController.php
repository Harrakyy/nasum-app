<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\PackageDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // <- TAMBAHKAN INI

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $bookings = Booking::with(['user', 'package', 'packageDate'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $bookings = $user->bookings()
                ->with(['package', 'packageDate'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($bookings);
    }

    public function confirmation(Request $request)
    {
        // booking_id dikirim dari redirect webStore
        $bookingId = session('booking_id');

        if (!$bookingId) {
            return redirect()->route('booking.form')
                ->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $booking = Booking::with(['user', 'package', 'packageDate'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('konfirmasi_pesanan', compact('booking'));
    }

    public function processConfirmation(Request $request)
    {
        $validated = $request->validate([
            'nama_jamaah' => 'required|string',
            'email' => 'required|email',
            'nomor_telepon' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required',
            'nama_darurat' => 'required',
            'nomor_darurat' => 'required',
        ]);

        $bookingId = session('booking_id');

        if (!$bookingId) {
            return redirect()->route('booking.form')
                ->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $booking = Booking::findOrFail($bookingId);
        $booking->update($validated);

        return redirect()->route('my.umrah')
            ->with('success', 'Konfirmasi pemesanan berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'package_date_id' => 'required|exists:package_dates,id',
            'room_type' => 'required|in:double,triple,quad',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);
        $packageDate = PackageDate::findOrFail($request->package_date_id);

        // Check availability
        if ($packageDate->available_slots <= 0) {
            return response()->json(['message' => 'No available slots for this date'], 400);
        }

        $totalPrice = $package->getPriceByRoomType($request->room_type);

        // Generate booking code
        $bookingCode = 'BOOK-' . strtoupper(Str::random(8));

        $booking = Booking::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_date_id' => $packageDate->id,
            'room_type' => $request->room_type,
            'total_price' => $totalPrice,
            'booking_code' => $bookingCode,
            'jumlah_jamaah' => 1,
            'customer_name' => $request->customer_name ?? $user->name,
            'customer_email' => $request->customer_email ?? $user->email,
            'customer_phone' => $request->customer_phone ?? $user->phone,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        // Decrease available slots
        $packageDate->decrement('available_slots');

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking->load(['package', 'packageDate'])
        ], 201);
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'package', 'packageDate'])->findOrFail($id);
        
        // Authorization check
        if (Auth::user()->id !== $booking->user_id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($booking);
    }

    public function updatePayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:unpaid,pending,paid,failed',
            'payment_method' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking->update([
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_status === 'paid' ? now() : null,
            'status' => $request->payment_status === 'paid' ? 'confirmed' : 'pending'
        ]);

        return response()->json([
            'message' => 'Payment status updated successfully',
            'booking' => $booking
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking = Booking::findOrFail($id);
        
        if (Auth::user()->id !== $booking->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $booking->update([
            'payment_proof' => $path,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Payment proof uploaded successfully',
            'booking' => $booking
        ]);
    }

    public function webStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'harga_total' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Find package
        $package = Package::findOrFail($request->package_id);
        
        // Find or create package date
        $packageDate = PackageDate::firstOrCreate(
            [
                'package_id' => $package->id,
                'departure_date' => $request->tanggal_keberangkatan,
            ],
            [
                'available_slots' => 20,
                'is_available' => true,
            ]
        );

        // Check availability
        if ($packageDate->available_slots <= 0) {
            return redirect()->back()
                ->with('error', 'Maaf, kuota untuk tanggal ini sudah penuh.')
                ->withInput();
        }

        // Calculate total price
        $totalPrice = $package->getPriceByRoomType($request->room_type);

        // Generate booking code
        $bookingCode = 'BOOK-' . strtoupper(Str::random(8));

        // Create booking
        $booking = Booking::create([
            // Required fields
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_date_id' => $packageDate->id,
            'room_type' => $request->room_type,
            'total_price' => $totalPrice,
            'booking_code' => $bookingCode,
            'jumlah_jamaah' => 1,
            
            // Customer information
            'customer_name' => $request->nama_jamaah,
            'customer_email' => $request->email,
            'customer_phone' => $request->nomor_telepon,
            
            // Address information
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            
            // Emergency contact
            'nama_darurat' => $request->nama_darurat,
            'nomor_darurat' => $request->nomor_darurat,
            
            // Status
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Decrease available slots
        $packageDate->decrement('available_slots');

        // Store booking ID in session
        session(['booking_id' => $booking->id]);

        return redirect()->route('booking.confirmation')
            ->with('success', 'Pemesanan berhasil! Silakan lanjutkan pembayaran.');
    }

    public function webUploadPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $booking = Booking::findOrFail($request->booking_id);
        
        if (Auth::user()->id !== $booking->user_id) {
            return redirect()->back()
                ->with('error', 'Akses ditolak.');
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $booking->update([
            'payment_proof' => $path,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending'
        ]);

        return redirect()->route('my.umrah')
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function adminVerifyPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required|in:unpaid,pending,paid,failed',
        ]);

        $booking->update([
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_status === 'paid' ? now() : null,
            'status' => $request->payment_status === 'paid' ? 'confirmed' : 'pending'
        ]);

        return redirect()->route('admin.verify.payments')
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function adminDelete(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.verify.payments')
            ->with('success', 'Data pendaftar berhasil dihapus.');
    }
}