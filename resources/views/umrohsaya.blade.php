<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Umroh Saya | Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style3.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Scheherazade&display=swap">
    <style>
        /* Styling khusus untuk halaman Umroh Saya */
        .profile-sidebar {
            padding-top: 50px;
        }

        .profile-card {
            border-right: 1px solid #eee;
        }

        .status-alert {
            padding: 20px;
            background-color: #f8f9fa;
            /* Light background */
            border-left: 5px solid #ffc107;
            /* Yellow border */
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand arabic-logo fw-bold" href="{{route('home')}}">Nasrotul Ummah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="{{route('home')}}">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('about')}}">Tentang Kami</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('packages')}}">Daftar Umroh</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('my.umrah')}}">Umroh Saya</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('contact')}}">Hubungi Kami</a></li>

                                                        {{-- HANYA MUNCUL JIKA SUDAH LOGIN --}}
                                                        @auth
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="{{ route('my.umrah') }}">Umroh Saya</a>
                                                            </li>

                                                            <li class="nav-item ms-2">
                                                                <form action="{{ route('logout') }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-light">
                                                                        Logout
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endauth

                                                        {{-- HANYA MUNCUL JIKA BELUM LOGIN --}}
                                                        @guest
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                                                            </li>
                                                        @endguest

                                                    </ul>

            </div>
        </div>
    </nav>

    <section class="umroh-saya-section py-5 mt-5">
        <div class="container">
            <div class="row">

                <div class="col-md-3 profile-sidebar">
                    <div class="profile-card p-3">
                        <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
                            <p class="text-muted small">{{ Auth::user()->email }}</p>
                        <ul class="list-unstyled mt-4">
                            <li><a href="#" class="text-dark text-decoration-none d-block py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-bag-check" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                        <path
                                            d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                                    </svg>
                                    Pesanan Umroh
                                </a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-9 pt-5">
                    @if($bookings->isEmpty())
                        <div class="status-alert rounded d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 fill="#ffc107" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-3"></svg>
                            <div>
                                <h5 class="mb-1 fw-bold">Belum Ada Pemesanan</h5>
                                <p class="mb-0 small">
                                    Jika Anda sudah melakukan pemesanan, status akan terlihat di sini.
                                </p>
                            </div>
                        </div>
                    @else

                        {{-- ⭐ LOOP BOOKING --}}
                        @foreach($bookings as $booking)

                            <div class="card mb-4 shadow-sm p-3">

                                <h5 class="fw-bold">
                                    Paket: {{ $booking->package->name ?? '-' }}
                                </h5>

                                <p class="small mb-1">Kode Booking:
                                    <span class="fw-bold">{{ $booking->booking_code }}</span>
                                </p>

                                <p class="small mb-1">Keberangkatan:
                                    <span class="fw-bold">
                                        {{ $booking->packageDate->display_date ?? '-' }}
                                    </span>
                                </p>

                                <p class="small mb-1">Total Pembayaran:
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </span>
                                </p>
                                <p class="small mb-1">Status Pembayaran:
                                   @if($booking->payment_status === 'paid')
                                    <span class="badge bg-success">Lunas</span>

                                @elseif($booking->payment_status === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>

                                @elseif(in_array($booking->payment_status, ['expire', 'cancel', 'deny']))
                                    <span class="badge bg-danger">Gagal</span>

                                @elseif($booking->payment_status === 'unpaid')
                                    <span class="badge bg-secondary">Belum Bayar</span>
                                @endif

                                </p>


                                <a href="{{ route('booking.detail', $booking->id) }}"
                                   class="btn btn-primary btn-sm mt-2">
                                    Detail Pemesanan
                                </a>

                                @if($booking->payment_status === 'pending')
                                    <a href="{{ route('booking.confirmation', $booking->id) }}"
                                    class="btn btn-success btn-sm mt-2">
                                        Bayar Sekarang
                                    </a>
                                @endif


                            </div>

                        @endforeach
                    @endif

                </div>

            </div>
        </div>
    </section>

    <footer class="bg text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Sosial Media</h5>
                    <p class="small">Jangan lewatkan informasi lainnya di sosial media rabbanihtour</p>
                    <ul class="list-unstyled small">
                        <li>@ummahTravel</li>
                        <li>@ummahTravel.pdg</li>
                        <li>@ummahTravel.bdg</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Jakarta</h5>
                    <p class="small">
                        Jl. RS. Fatmawati Raya No.215, RT.5/RW.3, Cilandak Barat,<br>
                        Kec. Cilandak, Kota Jakarta Selatan
                    </p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Padang</h5>
                    <p class="small">
                        Jl. Koto Tuo No.4, Balai Gadang,<br>
                        Kec. Koto Tangah, Kota Padang
                    </p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Bandung</h5>
                    <p class="small">
                        Jl. Jurang No.84, Pasteur, Kec. Sukajadi,<br>
                        Kota Bandung
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>