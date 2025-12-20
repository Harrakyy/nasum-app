<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Umroh Reguler | Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style3.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Scheherazade&display=swap">
    <style>
        .package-hero {
            background-color: #1A061F;
            padding: 80px 0;
            color: white;
            min-height: 50vh;
        }

        .package-image-card {
            /* Ganti gambar latar belakang */
            background: url('{{ asset("image/umroh_reguler_potrait.png") }}') no-repeat center center/cover, url('{{ asset("image/umroh_reguler_potrait.png") }}') no-repeat center center/cover;
            height: 700px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        .package-info-section {
            padding-top: 50px;
        }
        /* ... (facility-item styles, dll.) ... */
        .facility-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .facility-icon {
            font-size: 1.5rem;
            color: #00ff8c;
            margin-right: 15px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #00ff8c;
            border-radius: 50%;
        }

        .booking-cta {
            background-color: #1A061F;
            color: white;
            padding: 50px 0;
            text-align: center;
        }

        .btn-booking {
            background-color: #ffc107;
            border-color: #ffc107;
            color: black;
            font-weight: bold;
            padding: 10px 40px;
            font-size: 1.1rem;
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

    <section class="package-hero d-flex align-items-center mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-5 fw-bold">Umroh Reguler</h1>
                    <p class="lead mb-1">
                        <span class="badge bg-warning text-dark me-2">9 Hari</span>
                        <span class="badge bg-secondary me-2">Umroh Plus</span>
                        <span class="text-warning">★★★★★</span>
                    </p>
                    <p class="mt-4">
                        Paket Umroh yang ditawarkan oleh Nasrotul Ummah adalah salah satu pilihan untuk 
                        melaksanakan Ibadah Umroh ke Tanah Suci sekaligus berwisata ke kota Dubai.
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="package-image-card"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="package-info-section py-5 bg-light">
        <div class="container">
            <h3 class="fw-bold mb-4">Umroh Plus</h3>
            <p class="text-muted">
                Paket Umroh Hebat menawarkan pengalaman ibadah yang istimewa dengan fasilitas mewah, 
                pendampingan spiritual, dan pelayanan 24/7. Dengan Hotel bintang tiga-lima, 
                pendampingan oleh ustadz ahli, dan layanan tim yang siap membantu, Anda akan 
                merasakan kenyamanan dan keberkahan selama perjalanan. Ini adalah pilihan terbaik 
                untuk pengalaman umroh yang tak terlupakan dan mendalam di Tanah Suci.
            </p>

            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="facility-item">
                        <span class="facility-icon">✈️</span>
                        <div>
                            <small class="text-muted d-block">Flight Direct</small>
                            <p class="mb-0 fw-bold">Saudia Airlines</p>
                            <small class="text-success">Landing Jeddah</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="facility-item">
                        <span class="facility-icon">🏨</span>
                        <div>
                            <small class="text-muted d-block">Hotel Madinah</small>
                            <p class="mb-0 fw-bold">Concorde Al Khair 4*</p>
                            <span class="text-warning">★★★★</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="facility-item">
                        <span class="facility-icon">🕋</span>
                        <div>
                            <small class="text-muted d-block">Hotel Makkah</small>
                            <p class="mb-0 fw-bold">Shuhada 5*</p>
                            <span class="text-warning">★★★★★</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="facility-item">
                        <span class="facility-icon">🚌</span>
                        <div>
                            <small class="text-muted d-block">Include</small>
                            <p class="mb-0 fw-bold">Kereta Cepat</p>
                            <small class="text-muted">Haramain</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="facility-item">
                        <span class="facility-icon">🏙️</span>
                        <div>
                            <small class="text-muted d-block">Include</small>
                            <p class="mb-0 fw-bold">City Tour</p>
                            <small class="text-muted"></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="facility-item">
                        <span class="facility-icon">📘</span>
                        <div>
                            <small class="text-muted d-block">Free</small>
                            <p class="mb-0 fw-bold">Buku Umroh</p>
                            <small class="text-muted">Best Seller</small>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-end small text-muted mt-5">Info Paket Terupdate: 24 Mei 2025</p>
        </div>
    </section>

    <section class="booking-cta">
        <div class="container">
            <h3 class="fw-bold mb-3">Umroh Reguler 9 Hari</h3>
            <p>Dapatkan penawaran harga menarik sekarang juga sebelum kehabisan.</p>
            <a href="{{ route('booking.form') }}" class="btn btn-booking text-decoration-none">Pesan Sekarang</a>

        </div>
    </section>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4"><h5 class="text-success">Sosial Media</h5><p class="small">Jangan lewatkan informasi lainnya di sosial media rabbanihtour</p><ul class="list-unstyled small"><li>@ummahTravel</li><li>@ummahTravel.pdg</li><li>@ummahTravel.bdg</li></ul></div>
                <div class="col-md-3 mb-4"><h5 class="text-success">Kantor Jakarta</h5><p class="small">Jl. RS. Fatmawati Raya No.215, RT.5/RW.3, Cilandak Barat,<br>Kec. Cilandak, Kota Jakarta Selatan</p></div>
                <div class="col-md-3 mb-4"><h5 class="text-success">Kantor Padang</h5><p class="small">Jl. Koto Tuo No.4, Balai Gadang,<br>Kec. Koto Tangah, Kota Padang</p></div>
                <div class="col-md-3 mb-4"><h5 class="text-success">Kantor Bandung</h5><p class="small">Jl. Jurang No.84, Pasteur, Kec. Sukajadi,<br>Kota Bandung</p></div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="{{ asset('js/script3.js') }}"></script>

</body>

</html>