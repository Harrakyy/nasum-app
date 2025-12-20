<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
public function index()
{
    return $this->home();
}

    public function home()
    {
        // Cek dulu apakah tabel packages ada
        try {
            // Coba akses database
            \DB::connection()->getPdo();
            
            // Cek apakah tabel packages ada
            if (\Schema::hasTable('packages')) {
                $packages = Package::where('is_active', true)
                    ->with(['dates' => function($query) {
                        $query->where('is_available', true)
                            ->where('available_slots', '>', 0)
                            ->orderBy('departure_date');
                    }])
                    ->limit(3)
                    ->get();
            } else {
                $packages = collect([]);
            }
        } catch (\Exception $e) {
            // Jika ada error, tampilkan tanpa data
            $packages = collect([]);
        }
        
        return view('index', compact('packages'));
    }

    public function about()
    {
        return view('tentangkami');
    }

    public function packages()
    {
        try {
            if (\Schema::hasTable('packages')) {
                $packages = Package::where('is_active', true)
                    ->with(['dates' => function($query) {
                        $query->where('is_available', true)
                            ->where('available_slots', '>', 0)
                            ->orderBy('departure_date');
                    }])
                    ->get();
            } else {
                $packages = collect([]);
            }
        } catch (\Exception $e) {
            $packages = collect([]);
        }

        return view('daftarumroh', compact('packages'));
    }

    public function contact()
    {
        return view('hubungi-kami');
    }

    public function login()
    {
        return view('login');
    }

    public function packageDubai()
    {
        try {
            if (\Schema::hasTable('packages')) {
                $package = Package::where('slug', 'umroh-dubai')
                    ->where('is_active', true)
                    ->with(['dates' => function($query) {
                        $query->where('is_available', true)
                            ->where('available_slots', '>', 0)
                            ->orderBy('departure_date');
                    }])
                    ->first();
                
                if ($package) {
                    return view('detail_dubai', compact('package'));
                }
            }
        } catch (\Exception $e) {
            // Tangani error
        }
        
        return view('detail_dubai', ['package' => null]);
    }

    public function packageTurki()
    {
        try {
            if (\Schema::hasTable('packages')) {
                $package = Package::where('slug', 'umroh-turki')
                    ->where('is_active', true)
                    ->with(['dates' => function($query) {
                        $query->where('is_available', true)
                            ->where('available_slots', '>', 0)
                            ->orderBy('departure_date');
                    }])
                    ->first();
                
                if ($package) {
                    return view('detail_turki', compact('package'));
                }
            }
        } catch (\Exception $e) {
            // Tangani error
        }
        
        return view('detail_turki', ['package' => null]);
    }

    public function packageReguler()
    {
        try {
            if (\Schema::hasTable('packages')) {
                $package = Package::where('slug', 'umroh-reguler')
                    ->where('is_active', true)
                    ->with(['dates' => function($query) {
                        $query->where('is_available', true)
                            ->where('available_slots', '>', 0)
                            ->orderBy('departure_date');
                    }])
                    ->first();
                
                if ($package) {
                    return view('detail_reguler', compact('package'));
                }
            }
        } catch (\Exception $e) {
            // Tangani error
        }
        
        return view('detail_reguler', ['package' => null]);
    }

    public function myUmrah()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            if (\Schema::hasTable('bookings')) {
                $bookings = Auth::user()->bookings()
                    ->with(['package', 'packageDate'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $bookings = collect([]);
            }
        } catch (\Exception $e) {
            $bookings = collect([]);
        }

        return view('umrohsaya', compact('bookings'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ]);

        Auth::user()->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function bookingForm(Request $request)
{
    // Pastikan user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    try {
        // Ambil semua package yang aktif
        $packages = Package::where('is_active', true)->get();
        
        // Debug: log data packages
        \Log::info('Packages loaded for booking form:', [
            'count' => $packages->count(),
            'packages' => $packages->map(function($pkg) {
                return [
                    'id' => $pkg->id,
                    'name' => $pkg->name,
                    'double_price' => $pkg->double_price,
                    'triple_price' => $pkg->triple_price,
                    'quad_price' => $pkg->quad_price
                ];
            })->toArray()
        ]);
        
        // Jika tidak ada package, tampilkan error
        if ($packages->isEmpty()) {
            return redirect()->route('packages')
                ->with('error', 'Tidak ada paket umroh yang tersedia saat ini.');
        }
        
    } catch (\Exception $e) {
        \Log::error('Error in bookingForm: ' . $e->getMessage());
        $packages = collect([]); // Return empty collection
        
        return redirect()->route('packages')
            ->with('error', 'Terjadi kesalahan saat memuat data paket.');
    }

    return view('form_pemesanan', compact('packages'));
}
  

    public function adminDashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        try {
            if (\Schema::hasTable('users') && \Schema::hasTable('packages') && \Schema::hasTable('bookings')) {
                $totalUsers = User::count();
                $totalPackages = Package::count();
                $totalBookings = Booking::count();
                $pendingPayments = Booking::where('payment_status', 'pending')->count();
            } else {
                $totalUsers = 0;
                $totalPackages = 0;
                $totalBookings = 0;
                $pendingPayments = 0;
            }
        } catch (\Exception $e) {
            $totalUsers = 0;
            $totalPackages = 0;
            $totalBookings = 0;
            $pendingPayments = 0;
        }
        
        return view('admin.beranda', compact(
            'totalUsers',
            'totalPackages', 
            'totalBookings',
            'pendingPayments'
        ));
    }

    public function adminManagePackages()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        try {
            if (\Schema::hasTable('packages')) {
                $packages = Package::with('dates')->get();
            } else {
                $packages = collect([]);
            }
        } catch (\Exception $e) {
            $packages = collect([]);
        }
        
        return view('admin.kelola', compact('packages'));
    }

    public function adminVerifyPayments()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        try {
            if (\Schema::hasTable('bookings')) {
                $bookings = Booking::with(['user', 'package', 'packageDate'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $bookings = collect([]);
            }
        } catch (\Exception $e) {
            $bookings = collect([]);
        }

        return view('admin.pendaftar', compact('bookings'));
    }
}