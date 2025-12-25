<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pembayaran | Admin Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <style>
        /* CSS dari style3.css dan Navbar Styling */
        :root {
            --primary-dark: #1A061F;
            --secondary-green: #3eff3e;
        }

        .navbar,
        footer {
            background-color: var(--primary-dark) !important;
            color: white;
        }

        body {
            padding-top: 0;
            background-color: #f8f9fa;
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

        /* Gaya Kustom Halaman Daftar Pendaftar */
        .admin-section {
            padding-top: 100px;
            min-height: 80vh;
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table th {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .table td {
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .badge-pendaftar {
            background-color: var(--primary-dark);
            color: white;
            font-weight: normal;
        }

        .btn-aksi {
            color: #dc3545;
            /* Merah untuk ikon hapus */
            font-size: 1.1rem;
        }

        /* Gaya Khusus Modal Hapus */
        .btn-batal-modal {
            background-color: #e9ecef;
            color: var(--primary-dark);
        }

        .btn-hapus-konfirmasi {
            background-color: #dc3545;
            /* Merah untuk konfirmasi hapus */
            color: white;
            font-weight: bold;
        }

        /* Footer adjustments */
        footer h5 {
            color: #00ff8c !important;
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.manage.packages') }}">Kelola Paket Umroh</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.verify.payments') }}">Verifikasi Pembayaran</a></li>
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

    <main class="admin-section">
        <div class="container">
            <div class="table-container">

                <h5 class="fw-bold mb-4">
                    Daftar Pendaftar Umroh
                    <span class="badge badge-pendaftar rounded-pill ms-2">2 Pendaftar</span>
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Paket</th>
                                <th>Tanggal</th>
                                <th>Kamar</th>
                                <th>Harga</th>
                                <th>Status Bayar</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                                            @forelse ($bookings as $booking)
                                            <tr>
                                                <td>{{ $booking->user->name }}</td>
                                                <td>{{ $booking->user->email }}</td>
                                                <td>{{ $booking->user->phone ?? '-' }}</td>

                                                {{-- Nama Paket --}}
                                                <td>
                                                    Paket {{ 
                                                        str_replace(
                                                            ['+', 'Umroh'],
                                                            ['', 'Umroh '],
                                                            $booking->package->name
                                                        )
                                                    }}
                                                </td>

                                                {{-- Tanggal Keberangkatan --}}
                                                <td>
                                                    {{ 
                                                        $booking->packageDate 
                                                            ? \Carbon\Carbon::parse($booking->packageDate->departure_date)->translatedFormat('d F Y')
                                                            : '-' 
                                                    }}
                                                </td>

                                                {{-- Tipe Kamar --}}
                                                <td class="text-capitalize">
                                                    {{ $booking->room_type }}
                                                </td>

                                                {{-- Harga --}}
                                                <td class="fw-bold text-success">
                                                    Rp {{ number_format($booking->price, 0, ',', '.') }}
                                                </td>

                                                {{-- Status Bayar --}}
                                                <td>
                                                    @if($booking->payment_status === 'pending')
                                                        <span class="badge bg-danger">Belum Bayar</span>
                                                    @elseif($booking->payment_status === 'paid')
                                                        <span class="badge bg-success">Sudah Bayar</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $booking->payment_status }}</span>
                                                    @endif
                                                </td>

                                                {{-- Waktu Daftar --}}
                                                <td>
                                                    {{ $booking->created_at->translatedFormat('d F Y \p\u\k\u\l H.i') }}
                                                </td>

                                                {{-- Aksi --}}
                                                <td>
                                                    <button 
                                                            class="btn btn-link text-danger p-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#hapusModal{{ $booking->id }}"
                                                            title="Hapus Pendaftar">
                                                            <i class="bi bi-trash fs-5"></i>
                                                        </button>

                                                        {{-- Modal Konfirmasi --}}
                                                        <div class="modal fade" id="hapusModal{{ $booking->id }}" tabindex="-1">
                                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                                <div class="modal-content text-center p-3">

                                                                    <div class="modal-header border-0 justify-content-center">
                                                                        <h5 class="text-danger">
                                                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                                            Konfirmasi Hapus
                                                                        </h5>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <p class="mb-2">
                                                                            Yakin ingin menghapus pendaftar:
                                                                        </p>
                                                                        <strong>{{ $booking->user->name }}</strong>
                                                                        <p class="small text-muted mt-2">
                                                                            Data yang dihapus tidak dapat dikembalikan.
                                                                        </p>
                                                                    </div>

                                                                    <div class="modal-footer border-0 justify-content-center">
                                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                                            Batal
                                                                        </button>

                                                                        <form action="{{ route('admin.booking.delete', $booking->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                                Hapus
                                                                            </button>
                                                                        </form>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">
                                                    Belum ada pendaftar umroh
                                                </td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <div class="modal fade" id="hapusPendaftarModal" tabindex="-1" aria-labelledby="hapusPendaftarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-3">
                <div class="modal-header justify-content-center border-bottom-0">
                    <h5 class="modal-title text-danger" id="hapusPendaftarModalLabel"><i
                            class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Anda yakin ingin menghapus data pendaftar ini?</p>
                    <p class="small text-muted">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer justify-content-center border-top-0 pt-0">
                    <button type="button" class="btn btn-batal-modal" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-hapus-konfirmasi">Hapus Permanen</button>
                </div>
            </div>
        </div>
    </div>


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
                    <h5 class="text-success">Kantor Padang</h5>
                    <p class="small">Jl. Koto Tuo No.4, Balai Gadang,<br>Kec. Koto Tangah, Kota Padang</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-success">Kantor Bandung</h5>
                    <p class="small">Jl. Jurang No.84, Pasteur, Kec. Sukajadi,<br>Kota Bandung</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>