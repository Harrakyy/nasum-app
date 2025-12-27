<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket Umroh | Admin Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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

        /* Gaya Kustom Halaman Kelola Paket */
        .admin-section {
            padding-top: 100px;
            min-height: 80vh;
        }

        .package-card {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-top: 20px;
        }

        .price-list p {
            display: inline-block;
            margin-right: 25px;
            font-size: 0.95rem;
        }

        .date-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primary-dark);
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .date-item .actions a {
            color: #ffc107;
            margin-left: 10px;
        }

        .btn-tambah {
            background-color: var(--primary-dark);
            color: white;
            font-weight: bold;
        }

        /* Gaya Kustom Modal */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .modal-title {
            font-weight: bold;
            color: var(--primary-dark);
        }

        .modal-body label {
            font-weight: 500;
            color: var(--primary-dark);
        }

        .modal-body .form-control {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        /* Tombol Aksi Modal */
        .btn-batal {
            background-color: #e9ecef;
            color: var(--primary-dark);
        }

        .btn-simpan {
            background-color: var(--primary-dark);
            color: white;
            font-weight: bold;
        }

        /* Gaya Khusus Input Tanggal */
        .date-input-container {
            position: relative;
        }

        .date-input-container input[type="text"] {
            padding-right: 50px;
        }

        .date-input-container .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        /* Gaya Khusus Hapus Modal */
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
        :root {
            --primary-dark: #1A061F;
            --secondary-green: #3eff3e;
        }
        .navbar, footer { background-color: var(--primary-dark) !important; color: white; }
        body { background-color: #f8f9fa; }
        .admin-section { padding-top: 100px; min-height: 80vh; }
        .package-card { background: white; padding: 30px; border-radius: 8px; margin-top: 20px; }
        .date-item { background: var(--primary-dark); color: white; padding: 8px 15px; margin-bottom: 10px; border-radius: 6px; }
        .btn-tambah { background: var(--primary-dark); color:white }
        .btn-simpan { background: var(--primary-dark); color:white }
        .btn-batal { background:#eee }
        .btn-hapus-konfirmasi { background:#dc3545; color:white }
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.manage.packages') }}">Kelola Paket Umroh</a></li>
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

    <main class="admin-section">
        <div class="container">

            <div class="d-flex justify-content-end mb-4">
                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#tambahPaketModal">
                    <i class="bi bi-plus-lg"></i> Tambah Paket
                </button>
            </div>
             @foreach ($packages as $package)
    <div class="package-card">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-2">
            <h4 class="fw-bold">{{ $package->name }}</h4>

            <div>
                <a href="#" class="text-muted me-2" data-bs-toggle="modal"
                   data-bs-target="#editPaket{{ $package->id }}">
                    <i class="bi bi-pencil-square"></i>
                </a>

                {{-- NON-DESTRUKTIF --}}
                <form action="{{ route('admin.packages.destroy', $package->id) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Paket akan dinonaktifkan. Lanjutkan?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link text-danger p-0">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>

        <p class="text-muted">{{ $package->duration_days }} Hari</p>

        {{-- HARGA --}}
        <div class="price-list mb-3">
            <p>Double <b class="text-success">Rp {{ number_format($package->double_price) }}</b></p>
            <p>Triple <b class="text-success">Rp {{ number_format($package->triple_price) }}</b></p>
            <p>Quad <b class="text-success">Rp {{ number_format($package->quad_price) }}</b></p>
        </div>

        {{-- TANGGAL --}}
        <h6 class="fw-bold">Tanggal Keberangkatan</h6>

        @foreach ($package->dates as $date)
        <div class="date-item">
            <span>{{ $date->display_date }}</span>
            <form action="{{ route('admin.dates.destroy', $date->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-warning">Hapus</button>
            </form>
        </div>
        @endforeach

        {{-- TAMBAH TANGGAL --}}
        <form method="POST"
              action="{{ route('admin.packages.dates.store', $package->id) }}"
              class="mt-2">
            @csrf
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="date" name="departure_date" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="display_date" class="form-control"
                           placeholder="15 Januari 2025 (Rabu)" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark btn-sm w-100">Tambah</button>
                </div>
            </div>
        </form>

    </div>
    @endforeach

            <div class="package-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold">Paket Silver</h4>
                    <div class="actions">
                        <a href="#" class="text-muted me-2" title="Edit Paket" data-bs-toggle="modal"
                            data-bs-target="#editPaketModal">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="#" class="text-danger" title="Hapus Paket" data-bs-toggle="modal"
                            data-bs-target="#hapusPaketModal">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>

                <p class="text-muted small">15 Hari</p>

                <h6 class="fw-bold mb-3">Harga per Tipe Kamar</h6>
                <div class="price-list mb-4">
                    <p class="mb-0 fw-bold">Double <span class="text-success">Rp 30.000.000</span></p>
                    <p class="mb-0 fw-bold">Triple <span class="text-success">Rp 25.000.000</span></p>
                    <p class="mb-0 fw-bold">Quad <span class="text-success">Rp 22.000.000</span></p>
                </div>

                 <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold">Tanggal Keberangkatan</h6>
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#tambahTanggalModal">
                        <i class="bi bi-plus-lg"></i> Tambah Tanggal
                    </button>
                </div>

               <div class="date-item">
                    <span>12 Februari 2025 (Rabu)</span>
                </div>
                <div class="date-item">
                    <span>20 Januari 2025 (Rabu)</span>
                </div>
            </div>

        </div>
    </main>

    <div class="modal fade" id="tambahPaketModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <form method="POST" action="{{ route('admin.packages.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control mb-2" name="name" placeholder="Nama Paket" required>
                    <input class="form-control mb-2" name="duration_days" placeholder="Durasi (Hari)" required>
                    <input class="form-control mb-2" name="double_price" placeholder="Harga Double" required>
                    <input class="form-control mb-2" name="triple_price" placeholder="Harga Triple" required>
                    <input class="form-control mb-2" name="quad_price" placeholder="Harga Quad" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-simpan">Simpan</button>
                </div>
                </form>
                </div>
                </div>
                </div>


    <div class="modal fade" id="editPaketModal" tabindex="-1" aria-labelledby="editPaketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaketModalLabel">Edit Paket Silver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3 g-2">
                            <div class="col-8">
                                <label for="editNamaPaket" class="form-label small">Nama Paket *</label>
                                <input type="text" class="form-control" id="editNamaPaket" value="Paket Silver"
                                    required>
                            </div>
                            <div class="col-4">
                                <label for="editDurasi" class="form-label small">Durasi *</label>
                                <input type="text" class="form-control" id="editDurasi" value="15 Hari" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small d-block">Harga per Tipe Kamar *</label>
                            <div class="input-group mb-2"><span
                                    class="input-group-text small bg-white text-muted">Double (Rp)</span><input
                                    type="number" class="form-control" value="30000000" required></div>
                            <div class="input-group mb-2"><span
                                    class="input-group-text small bg-white text-muted">Triple (Rp)</span><input
                                    type="number" class="form-control" value="25000000" required></div>
                            <div class="input-group mb-2"><span class="input-group-text small bg-white text-muted">Quad
                                    (Rp)</span><input type="number" class="form-control" value="22000000" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-simpan">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahTanggalModal" tabindex="-1" aria-labelledby="tambahTanggalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTanggalModalLabel">Tambah Tanggal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Tambahkan tanggal baru untuk paket ini</p>
                    <form>
                        <div class="mb-3">
                            <label for="tanggalBaru" class="form-label small">Tanggal *</label>
                            <div class="date-input-container">
                                <input type="text" class="form-control" id="tanggalBaru"
                                    placeholder="Contoh: 15 Januari 2025" required>
                                <i class="bi bi-calendar input-icon"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggalDenganHari" class="form-label small">Tanggal dengan Hari</label>
                            <div class="date-input-container">
                                <input type="text" class="form-control" id="tanggalDenganHari"
                                    placeholder="Contoh: 15 Januari 2025 (Rabu)" required>
                                <i class="bi bi-calendar input-icon"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hapusPaketModal" tabindex="-1" aria-labelledby="hapusPaketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-3">
                <div class="modal-header justify-content-center border-bottom-0">
                    <h5 class="modal-title text-danger" id="hapusPaketModalLabel"><i
                            class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Anda yakin ingin menghapus **Paket Silver**?</p>
                    <p class="small text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer justify-content-center border-top-0 pt-0">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-hapus-konfirmasi">Hapus Permanen</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTanggalModal" tabindex="-1" aria-labelledby="editTanggalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTanggalModalLabel">Edit Tanggal Keberangkatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Edit tanggal keberangkatan ini</p>
                    <form>
                        <div class="mb-3">
                            <label for="editTanggalBaru" class="form-label small">Tanggal *</label>
                            <div class="date-input-container">
                                <input type="text" class="form-control" id="editTanggalBaru" value="12 Februari 2025"
                                    required>
                                <i class="bi bi-calendar input-icon"></i>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editTanggalDenganHari" class="form-label small">Tanggal dengan Hari</label>
                            <div class="date-input-container">
                                <input type="text" class="form-control" id="editTanggalDenganHari"
                                    value="12 Februari 2025 (Rabu)" required>
                                <i class="bi bi-calendar input-icon"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-simpan">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hapusTanggalModal" tabindex="-1" aria-labelledby="hapusTanggalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-3">
                <div class="modal-header justify-content-center border-bottom-0">
                    <h5 class="modal-title text-danger" id="hapusTanggalModalLabel"><i
                            class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Anda yakin ingin menghapus tanggal **12 Februari 2025 (Rabu)**?</p>
                    <p class="small text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer justify-content-center border-top-0 pt-0">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
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