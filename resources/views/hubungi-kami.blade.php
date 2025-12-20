<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hubungi Kami</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/style3.css') }}" />
</head>
<body>

  <!-- Navbar -->
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

  <!-- Konten Hubungi Kami -->
  <section class="contact-section py-5 mt-5">
    <div class="container">
      <h2 class="mb-4 fw-bold">Hubungi Kami</h2>
      <p>Kami ingin mendengar pendapat Anda. Silakan isi formulir ini atau kirimkan email kepada kami.</p>
      
      <div class="row g-5">
        
        <div class="col-md-5">
            <div class="d-flex mb-3">
                <div class="me-4">
                    <h5 class="fw-bold">Email</h5>
                    <p class="small">Tim kami siap membantu.</p>
                    <a href="mailto:NasrotulUmmah@gmail.com" class="text-success fw-bold small">NasrotulUmmah@gmail.com</a>
                </div>
                <div>
                    <h5 class="fw-bold">Telepon</h5>
                    <p class="small">Senin - Jumat dari 09.00 - 17.00 WIB</p>
                    <a href="tel:+6285710615365" class="text-success fw-bold small">+62 857-1061-5365</a>
                </div>
            </div>
            
            <h5 class="fw-bold mt-4">Kantor</h5>
            <p class="small">Ayo sapa di kantor pusat kami.</p>
            <p class="small">
                <a href="https://maps.google.com/?q=Jl.+RS.+Fatmawati+Raya+No.215" target="_blank" class="text-success fw-bold">
                    Jl. RS. Fatmawati Raya No 215, RT 5 RW 3,<br>
                    Cilandak, Kota Jakarta Selatan, DKI Jakarta
                </a>
            </p>
        </div>

        <div class="col-md-7">
          <div class="p-4 bg-white rounded shadow-sm">
            <h5 class="fw-bold mb-4">Kirim pesan kepada kami</h5>
            <form id="contactForm" novalidate>
               <div class="mb-3">
                <label for="nama_perusahaan" class="form-label visually-hidden">Nama Perusahaan*</label>
                <input type="text" class="form-control" id="nama_perusahaan" placeholder="Nama perusahaan*" required />
                <div class="invalid-feedback">Nama Perusahaan wajib diisi.</div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nama_depan" class="form-label visually-hidden">Nama Depan*</label>
                    <input type="text" class="form-control" id="nama_depan" placeholder="Nama depan*" required />
                    <div class="invalid-feedback">Nama Depan wajib diisi.</div>
                </div>
                <div class="col-md-6">
                    <label for="nama_belakang" class="form-label visually-hidden">Nama Belakang</label>
                    <input type="text" class="form-control" id="nama_belakang" placeholder="Nama belakang" />
                </div>
              </div>

               <div class="mb-3">
                <label for="jabatan" class="form-label visually-hidden">Jabatan Anda*</label>
                <input type="text" class="form-control" id="jabatan" placeholder="Jabatan anda*" required />
                <div class="invalid-feedback">Jabatan wajib diisi.</div>
              </div>
              
              <div class="mb-3">
                <label for="email_perusahaan" class="form-label visually-hidden">Email perusahaan*</label>
                <input type="email" class="form-control" id="email_perusahaan" placeholder="Email perusahaan*" required />
                <div class="invalid-feedback">Email tidak valid.</div>
              </div>
              
               <div class="mb-3">
                <label for="nomor_telepon" class="form-label visually-hidden">Nomor Telepon*</label>
                <input type="text" class="form-control" id="nomor_telepon" placeholder="Nomor telepon*" required />
                <div class="invalid-feedback">Nomor wajib diisi.</div>
              </div>
              
              <div class="mb-3">
                <label for="pesan" class="form-label visually-hidden">Bagaimana kami dapat membantu anda?*</label>
                <textarea class="form-control" id="pesan" rows="4" placeholder="Bagaimana kami dapat membantu anda?*" required></textarea>
                <div class="invalid-feedback">Pesan tidak boleh kosong.</div>
              </div>
              
              <button type="submit" class="btn btn-warning w-100 fw-bold">Kirim</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </section>

   <!-- Footer -->
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
  <script src="{{ asset('js/script3.js') }}"></script>

</body>
</html>
