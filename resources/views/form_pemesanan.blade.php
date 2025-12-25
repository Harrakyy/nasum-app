<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Pemesanan | Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style3.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Scheherazade&display=swap">
    <style>
    
        
        /* Warna Navbar dan Footer */
        .navbar, footer {
            background-color: #1A061F !important;
        }

        /* Jarak Konten Utama dari Navbar Fixed */
        .form-section {
            padding-top: 100px;
            padding-bottom: 50px;
        }

        /* Box untuk setiap section (mirip card) */
        .section-box {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Sedikit shadow */
        }

        .section-box h5 {
            color: #1A061F;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        /* Penyesuaian Ringkasan Harga (Kolom Kiri Bawah) */
        .summary-box {
            background-color: #f8f9fa; /* Latar belakang abu muda */
            border: 1px solid #dee2e6;
        }
        .summary-box p {
            margin-bottom: 8px;
        }
        .summary-box .highlight {
            background-color: #ffc107; /* Warna kuning */
            padding: 10px;
            border-radius: 5px;
            font-size: small;
            margin-top: 15px;
            color: #1A061F;
            font-weight: bold;
        }
        
        /* Input Tipe Kamar (Tombol Radio Custom) */
        .room-type-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .room-type-label:hover {
            background-color: #f1f1f1;
        }
        /* Style saat radio button dipilih */
        input[type="radio"]:checked + .room-type-label {
            border-color: #1A061F;
            background-color: #f0f0ff; 
        }
        .room-price {
            background-color: #1A061F;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* Logo di Navbar */
        .navbar-brand.arabic-logo {
            font-family: 'Scheherazade', serif;
            font-size: 1.5rem;
            color: white;
        }

        /* Tombol Aksi */
        .btn-kembali {
            background-color: #1A061F;
            color: white;
            border: none;
            padding: 10px 30px;
        }
        .btn-lanjutkan {
            background-color: #00ff8c; /* Warna hijau muda sebagai aksi utama */
            color: #1A061F;
            border: none;
            padding: 10px 30px;
            font-weight: bold;
        }
        
        /* Penyesuaian Footer */
        footer h5 {
            color: #00ff8c !important; /* Mengembalikan warna hijau untuk judul footer */
        }
    </style>
</head>

<body>

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

    <section class="form-section bg-light">
        <div class="container">
            <div class="row">

                <div class="col-lg-4">

                    <div class="section-box">
                        <h5 class="mb-3">Paket Yang Dipilih *</h5>

                        <div class="mb-3">
    <label class="form-label small mb-0">Paket Yang Dipilih *</label>
    <select id="paket_dipilih"
        name="package_id"
        class="form-select @error('package_id') is-invalid @enderror"
        required>
        <option value="">-- Pilih Paket --</option>
        @if($packages && $packages->count() > 0)
            @foreach($packages as $package)
                <option 
                    value="{{ $package->id }}"
                    data-double="{{ $package->double_price }}"
                    data-triple="{{ $package->triple_price }}"
                    data-quad="{{ $package->quad_price }}"
                    data-name="{{ $package->name }}"
                    {{ old('package_id') == $package->id ? 'selected' : '' }}
                >
                    {{ $package->name }} - {{ $package->duration_days }} Hari
                    @php
                        // Tampilkan jenis paket
                        if($package->type == ' paket Umroh + Dubai') {
                            echo '(Umroh + Dubai)';
                        } elseif($package->type == 'Paket Umroh + Turki') {
                            echo '(Umroh + Turki)';
                        } else {
                            echo '( PaketUmroh Reguler)';
                        }
                    @endphp
                </option>
            @endforeach
        @else
            <option value="" disabled>Tidak ada paket tersedia</option>
        @endif
    </select>
    @error('package_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                        <h5 class="mb-3">Tanggal Keberangkatan *</h5>
                        
                        <div class="mb-3">
                            <input type="date"
                                class="form-control"
                                id="tanggal_keberangkatan"
                                name="tanggal_keberangkatan"
                                required>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Tipe Kamar *</h5>
                        
                        <div>
                            <input type="radio" id="double" name="room_type" value="double" class="d-none">
                                <label for="double" class="room-type-label">
                                    <p class="mb-0 fw-bold">Double</p>
                                    <span class="room-price" id="price-double">Rp -</span>
                                </label>

                                <input type="radio" id="triple" name="room_type" value="triple" class="d-none">
                                <label for="triple" class="room-type-label">
                                    <p class="mb-0 fw-bold">Triple</p>
                                    <span class="room-price" id="price-triple">Rp -</span>
                                </label>

                                <input type="radio" id="quad" name="room_type" value="quad" class="d-none">
                                <label for="quad" class="room-type-label">
                                    <p class="mb-0 fw-bold">Quad</p>
                                    <span class="room-price" id="price-quad">Rp -</span>
                                </label>
                        </div>
                    </div>

                   <div class="section-box summary-box">
                            <h5 class="mb-3">Ringkasan Harga</h5>

                            <p>
                                Paket:
                                <span class="float-end fw-bold" id="summary-package">-</span>
                            </p>

                            <p>
                                Tanggal Keberangkatan:
                                <span class="float-end fw-bold" id="summary-date">-</span>
                            </p>

                            <p>
                                Tipe Kamar:
                                <span class="float-end fw-bold" id="summary-room">-</span>
                            </p>

                            <hr>

                            <p class="fw-bold fs-5">
                                Total Per Orang
                                <span class="float-end text-success" id="summary-price">Rp -</span>
                            </p>

                            <div class="highlight text-center">
                                *Hemat lebih banyak dengan memilih kamar Triple atau Quad!
                            </div>
                        </div>

                </div>

                <div class="col-lg-8">
                  
    <form method="POST" action="{{ route('booking.store') }}">
    @csrf
    <input type="hidden" name="package_id" id="package_id" value="">
    <input type="hidden" name="room_type" id="room_type" value="">
    <input type="hidden" name="tanggal_keberangkatan" id="tanggal_form" value="">
    <input type="hidden" name="harga_total" id="harga_total" value="">

    <div class="section-box">
        <h5 class="mb-4">Data Diri Jamaah</h5>

        <div class="mb-3">
            <label class="form-label small mb-0">Nama Lengkap *</label>
            <input type="text"
                   class="form-control @error('nama_jamaah') is-invalid @enderror"
                   name="nama_jamaah"
                   placeholder="Sesuai KTP/Paspor"
                   value="{{ old('nama_jamaah') }}"
                   required>
            @error('nama_jamaah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label small mb-0">Email *</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-0">Nomor Telepon *</label>
                <input type="text"
                       class="form-control @error('nomor_telepon') is-invalid @enderror"
                       name="nomor_telepon"
                       value="{{ old('nomor_telepon') }}"
                       required>
                @error('nomor_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small mb-0">Alamat Lengkap *</label>
            <input type="text"
                   class="form-control @error('alamat') is-invalid @enderror"
                   name="alamat"
                   value="{{ old('alamat') }}"
                   required>
            @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label small mb-0">Kota/Kabupaten *</label>
                <input type="text"
                       class="form-control @error('kota') is-invalid @enderror"
                       name="kota"
                       value="{{ old('kota') }}"
                       required>
                @error('kota')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-0">Provinsi *</label>
                <input type="text"
                       class="form-control @error('provinsi') is-invalid @enderror"
                       name="provinsi"
                       value="{{ old('provinsi') }}"
                       required>
                @error('provinsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3 col-md-4 px-0">
            <label class="form-label small mb-0 ps-3">Kode Pos *</label>
            <input type="text"
                   class="form-control @error('kode_pos') is-invalid @enderror"
                   name="kode_pos"
                   value="{{ old('kode_pos') }}"
                   required>
            @error('kode_pos')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="section-box">
        <h5 class="mb-4">Kontak Darurat</h5>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label small mb-0">Nama Kontak Darurat *</label>
                <input type="text"
                       class="form-control @error('nama_darurat') is-invalid @enderror"
                       name="nama_darurat"
                       value="{{ old('nama_darurat') }}"
                       required>
                @error('nama_darurat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-0">Nomor Telepon Darurat *</label>
                <input type="text"
                       class="form-control @error('nomor_darurat') is-invalid @enderror"
                       name="nomor_darurat"
                       value="{{ old('nomor_darurat') }}"
                       required>
                @error('nomor_darurat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('packages') }}" class="btn btn-kembali me-3">
            Kembali
        </a>

        <button type="submit" class="btn btn-lanjutkan" id="submitBtn">
            Lanjutkan
        </button>
    </div>
</form>

                </div>
            </div>
        </div>
    </section>

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
                    <p class="small">Jl. RS. Fatmawati Raya No.215, RT.5/RW.3, Cilandak Barat,<br>Kec. Cilandak, Kota Jakarta Selatan</p>
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
    document.addEventListener('DOMContentLoaded', function () {
    // 1. Elements
    const paketSelect = document.getElementById('paket_dipilih');
    const roomRadios = document.querySelectorAll('input[name="room_type"]');
    const tanggalInput = document.getElementById('tanggal_keberangkatan');
    
    // Hidden fields
    const packageIdField = document.getElementById('package_id');
    const roomTypeField = document.getElementById('room_type');
    const tanggalField = document.getElementById('tanggal_form');
    const hargaField = document.getElementById('harga_total');
    
    // Summary elements
    const summaryPackage = document.getElementById('summary-package');
    const summaryRoom = document.getElementById('summary-room');
    const summaryPrice = document.getElementById('summary-price');
    const summaryDate = document.getElementById('summary-date');
    
    // Form elements
    const form = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // 2. Helper Functions
    function formatRupiah(angka) {
        if (!angka || angka === '0' || angka === 'null' || angka === 'undefined') return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(parseInt(angka));
    }
    
    function formatTanggalIndonesia(dateString) {
        if (!dateString) return '-';
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
    
    // 3. Cek apakah ada data packages
    if (paketSelect) {
        console.log('Package select found, options:', paketSelect.options.length);
        
        // Jika hanya ada 1 option (placeholder), tampilkan warning
        if (paketSelect.options.length <= 1) {
            console.warn('No packages available in select');
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning';
            alertDiv.innerHTML = 'Tidak ada paket umroh yang tersedia. Silakan hubungi admin.';
            paketSelect.parentNode.insertBefore(alertDiv, paketSelect);
        }
    }
    
    // 4. Event Listeners
    // Update package selection
    if (paketSelect) {
        paketSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            
            if (selected && selected.value) {
                packageIdField.value = selected.value;
                summaryPackage.innerText = selected.dataset.name || '-';
                
                // Update prices
                const doublePrice = selected.dataset.double || '0';
                const triplePrice = selected.dataset.triple || '0';
                const quadPrice = selected.dataset.quad || '0';
                
                console.log('Package selected:', {
                    name: selected.dataset.name,
                    double: doublePrice,
                    triple: triplePrice,
                    quad: quadPrice
                });
                
                document.getElementById('price-double').innerText = formatRupiah(doublePrice);
                document.getElementById('price-triple').innerText = formatRupiah(triplePrice);
                document.getElementById('price-quad').innerText = formatRupiah(quadPrice);
                
                // Reset room selection
                roomRadios.forEach(radio => radio.checked = false);
                if (roomTypeField) roomTypeField.value = '';
                if (hargaField) hargaField.value = '';
                if (summaryRoom) summaryRoom.innerText = '-';
                if (summaryPrice) summaryPrice.innerText = 'Rp -';
            }
        });
    }
    
    // Update room selection
    roomRadios.forEach(radio => {
    radio.addEventListener('change', function () {
        const selectedPackage = paketSelect.options[paketSelect.selectedIndex];
        if (!selectedPackage) return;

        roomTypeField.value = this.value;
        summaryRoom.innerText = this.value.toUpperCase();

        let price = 0;
        if (this.value === 'double') price = selectedPackage.dataset.double;
        if (this.value === 'triple') price = selectedPackage.dataset.triple;
        if (this.value === 'quad')   price = selectedPackage.dataset.quad;

        hargaField.value = price;
        summaryPrice.innerText = formatRupiah(price);
    });
});
    
    // Update date selection
    if (tanggalInput) {
        tanggalInput.addEventListener('change', function () {
            if (tanggalField) {
                tanggalField.value = this.value;
            }
            
            if (summaryDate) {
                summaryDate.innerText = formatTanggalIndonesia(this.value);
            }
        });
    }
    
    // 5. Set initial values
    if (paketSelect && paketSelect.value) {
        const selected = paketSelect.options[paketSelect.selectedIndex];
        
        if (packageIdField) {
            packageIdField.value = paketSelect.value;
        }
        
        if (summaryPackage && selected) {
            summaryPackage.innerText = selected.dataset.name || '-';
        }
        
        // Initialize prices if package is already selected
        if (selected) {
            const doublePrice = selected.dataset.double || '0';
            const triplePrice = selected.dataset.triple || '0';
            const quadPrice = selected.dataset.quad || '0';
            
            document.getElementById('price-double').innerText = formatRupiah(doublePrice);
            document.getElementById('price-triple').innerText = formatRupiah(triplePrice);
            document.getElementById('price-quad').innerText = formatRupiah(quadPrice);
        }
    }
    
    if (tanggalInput && tanggalInput.value) {
        if (tanggalField) {
            tanggalField.value = tanggalInput.value;
        }
        
        if (summaryDate) {
            summaryDate.innerText = formatTanggalIndonesia(tanggalInput.value);
        }
    }
    
    // Auto select today's date if not set
    if (tanggalInput && !tanggalInput.value) {
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.value = today;
        
        if (tanggalField) {
            tanggalField.value = today;
        }
        
        if (summaryDate) {
            summaryDate.innerText = formatTanggalIndonesia(today);
        }
    }
    
    // 6. Form validation before submit
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Check if all required selection fields are filled
            const errors = [];
            
            if (!packageIdField || !packageIdField.value || (paketSelect && paketSelect.value === '')) {
                errors.push('Silakan pilih paket umroh');
                if (paketSelect) paketSelect.classList.add('is-invalid');
            } else if (paketSelect) {
                paketSelect.classList.remove('is-invalid');
            }
            
            if (!roomTypeField || !roomTypeField.value) {
                errors.push('Silakan pilih tipe kamar');
                document.querySelector('input[name="room_type"]:checked')?.classList.add('is-invalid');
            } else {
                document.querySelectorAll('input[name="room_type"]').forEach(radio => {
                    radio.classList.remove('is-invalid');
                });
            }
            
            if (!tanggalField || !tanggalField.value) {
                errors.push('Silakan pilih tanggal keberangkatan');
                if (tanggalInput) tanggalInput.classList.add('is-invalid');
            } else if (tanggalInput) {
                tanggalInput.classList.remove('is-invalid');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Harap lengkapi semua data yang diperlukan:\n\n' + errors.join('\n'));
                return false;
            }
            
            // Disable button to prevent double click
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        });
    }
});
</script>

</body>

</html>