<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Konfirmasi Pemesanan | Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style3.css') }}"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Scheherazade&display=swap">
    <style>
       
        
        /* Jarak Konten Utama dari Navbar Fixed */
        .confirmation-section {
            padding-top: 100px;
            padding-bottom: 50px;
            min-height: 80vh; /* Agar footer berada di bawah */
            background-color: #f8f9fa; /* Background abu-abu muda */
        }

        /* Card Konten */
        .content-card {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
            height: 350px; /* Tinggi disesuaikan agar card tampak seragam */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
        }

        /* Tombol Aksi */
        .btn-kembali {
            background-color: #1A061F;
            color: white;
            border: none;
            padding: 10px 30px;
        }
        .btn-lanjutkan {
            background-color: #4b0082; /* Warna ungu tua/indigo sebagai pembeda aksi utama, sesuai contoh gambar */
            color: white;
            border: none;
            padding: 10px 30px;
            font-weight: bold;
        }
        
        /* Konsistensi Warna Navbar/Footer */
        .navbar, footer {
            background-color: #1A061F !important;
        }
        footer h5 {
            color: #00ff8c !important; /* Warna hijau untuk judul footer */
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand arabic-logo" href="{{route('home')}}">Nasrotul Ummah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="{{route('home')}}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('about')}}">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('packages')}}">Daftar Umroh</a></li>

                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{route('my.umrah')}}">Umroh Saya</a></li>
                        <li class="nav-item ms-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @endguest
                </ul>

            </div>
        </div>
    </nav>


    {{-- CONTENT --}}
    <section class="confirmation-section">
        <div class="container">

            <div class="row g-4">

                {{-- RINGKASAN PESANAN --}}
                <div class="col-md-6">
                    <div class="content-card">
                        <h4 class="fw-bold mb-4">Ringkasan Pesanan</h4>

                        <div class="mb-3">
                            <p class="mb-1 text-muted small">Nama Jamaah</p>
                            <p class="fw-bold">{{ $booking->customer_name }}</p>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1 text-muted small">Tipe Kamar</p>
                                <p class="fw-bold text-uppercase">{{ ucfirst($booking->room_type) }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1 text-muted small">Paket</p>
                                <p class="fw-bold text-success fs-4">Paket {{ str_replace('+', '', $booking->package->name) }}</p>
                            </div>
                        </div>

                        <hr class="mt-4">

                        <p class="fw-bold fs-5">Total Pembayaran</p>
                        <p class="fw-bold text-success fs-4">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="col-md-6">
                    <div class="content-card">
                        <h4 class="fw-bold mb-4">Metode Pembayaran</h4>
                        <p class="text-muted">Pilih metode pembayaran yang Anda inginkan (VA, Transfer Bank, dll.)</p>

                        {{-- TOMBOL SNAP MIDTRANS --}}
                        <button id="pay-button" class="btn btn-primary">
                            Bayar Sekarang
                        </button>

                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('booking.form') }}" class="btn btn-kembali me-3 text-decoration-none">Kembali</a>
                <a href="{{ route('my.umrah') }}" class="btn btn-lanjutkan">Lanjutkan</a>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="text-white py-5">
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
                    <p class="small">Jl. RS. Fatmawati Raya No.215, RT.5/RW.3, Cilandak Barat</p>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Padang</h5>
                    <p class="small">Jl. Koto Tuo No.4, Balai Gadang</p>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="text-warning">Kantor Bandung</h5>
                    <p class="small">Jl. Jurang No.84, Pasteur</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- SCRIPT BOOTSTRAP --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- MIDTRANS SNAP --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay('{{ $booking->snap_token }}', {
                onSuccess: function(result){
                    window.location.href = "{{ route('my.umrah') }}";
                },
                onPending: function(result){
                    alert("Pembayaran pending");
                },
                onError: function(result){
                    alert("Pembayaran gagal");
                },
                onClose: function(){
                    alert("Anda menutup pop-up tanpa menyelesaikan pembayaran");
                }
            });
        });
    </script>

</body>
</html>