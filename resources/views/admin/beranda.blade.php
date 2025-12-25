<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <style>
        /* CSS dari style3.css */
        :root {
            --primary-dark: #1A061F;
            --secondary-green: #3eff3e;
            /* Sesuai dengan highlight navbar di style3.css */
        }

        .navbar,
        footer {
            background-color: var(--primary-dark) !important;
            color: white;
        }

        body {
            padding-top: 0;
        }

        .navbar-brand.arabic-logo {
            font-family: 'Scheherazade', serif;
            font-size: 1.5rem;
            color: white;
        }

        @import url('https://fonts.googleapis.com/css2?family=Scheherazade&display=swap');

        .navbar .nav-link {
            color: white;
            position: relative;
            margin-left: 15px;
        }

        .navbar .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 100%;
            height: 3px;
            background-color: orange;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .navbar .nav-link.active::after,
        .navbar .nav-link:hover::after {
            transform: scaleX(1);
        }

        /* Akhir CSS dari style3.css */


        /* Gaya Kustom Dashboard */
        .stat-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .stat-icon {
            font-size: 1.5rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .package-list .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border: none;
            border-bottom: 1px solid #f8f9fa;
        }

        .package-list .list-group-item:last-child {
            border-bottom: none;
        }

        .badge-jamaah {
            background-color: var(--primary-dark);
            color: white;
            font-weight: normal;
        }

        footer h5 {
            color: #00ff8c !important;
            /* Menggunakan warna hijau dari footer file lain */
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand arabic-logo" href="{{ route('admin.dashboard') }}">Nasrotul Ummah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.dashboard') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.manage.packages') }}">Kelola Paket Umroh</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.verify.payments') }}">Verifikasi Pembayaran</a></li>
                                                            {{-- JIKA SUDAH LOGIN --}}
                                            @auth

                                                {{-- KHUSUS ADMIN --}}
                                                @if(auth()->user()->role === 'admin')
            
                                                    <li class="nav-item ms-2">
                                                        <form action="{{ route('logout') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-light">
                                                                Logout
                                                            </button>
                                                        </form>
                                                    </li>

                                                {{-- KHUSUS USER --}}
                                                @else
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{{ route('my.umrah') }}">
                                                            Umroh Saya
                                                        </a>
                                                    </li>

                                                    <li class="nav-item ms-2">
                                                        <form action="{{ route('logout') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-light">
                                                                Logout
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif

                                            @endauth


                                            {{-- JIKA BELUM LOGIN --}}
                                            @guest
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('login') }}">
                                                        Login
                                                    </a>
                                             </li>
                                            @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5" style="padding-top: 100px !important;">

        <div class="row mb-5 g-4">

            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-people stat-icon"></i>
                    <h2 class="fw-bold">{{ $totalPendaftar }}</h2>
                    <p class="text-muted small mb-0">Total Pendaftar</p>
                    <p class="text-secondary mb-0 small mt-1">Jamaah terdaftar</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-bag-check stat-icon"></i>
                    <h2 class="fw-bold">{{ $totalPaket }}</h2>
                    <p class="text-muted small mb-0">Total Paket</p>
                    <p class="text-secondary mb-0 small mt-1">Paket Tersedia</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-calendar-check stat-icon"></i>
                    <h2 class="fw-bold">{{ $totalTanggal = \DB::table('package_dates')->count(); }}</h2>
                    <p class="text-muted small mb-0">Total Tanggal</p>
                    <p class="text-secondary mb-0 small mt-1">Tanggal Tersedia</p>
                </div>
            </div>

        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Pendaftar per Paket</h5>
                            <ul class="list-group list-group-flush package-list">
                                @foreach($pendaftarPerPaket as $paket)
                                    <li class="list-group-item">
                                        <span class="text-muted">
                                            Paket {{ trim(str_replace('+', '', $paket->name)) }}
                                        </span>
                                        <span class="badge badge-jamaah rounded-pill">
                                            {{ $paket->total_jamaah }} Jamaah
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                        <i class="bi bi-credit-card-2-front fs-3 text-warning"></i>
                        <h5 class="card-title fw-bold mt-2">Pembayaran Menunggu Verifikasi</h5>
                        <h3 class="fw-bold text-danger"> {{ $pendingPayments }} Pembayaran</h3>
                        <p class="text-muted small mt-3">Perlu diverifikasi di tab **"Verifikasi Pembayaran"**</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-white py-5 mt-5">
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
                    <p class="small">Jl. RS. Fatmawati Raya No.215, RT.5/RW.3, Cilandak Barat,<br>Kec. Cilandak, Kota
                        Jakarta Selatan</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Padang</h5>
                    <p class="small">Jl. Koto Tuo No.4, Balai Gadang,<br>Kec. Koto Tangah, Kota Padang</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Bandung</h5>
                    <p class="small">Jl. Jurang No.84, Pasteur, Kec. Sukajadi,<br>Kota Bandung</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const navLinks = document.querySelectorAll('.nav-link'); //

        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                navLinks.forEach(l => l.classList.remove('active')); //
                this.classList.add('active'); //
            });
        });
    </script>
</body>

</html>