<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Nasrotul Ummah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Scheherazade&display=swap">
    <style>
        /* CSS Kustom untuk Tampilan Login */

        body {
            /* Latar belakang yang menyerupai gambar */
            background: url('{{ asset("image/beranda.png") }}') no-repeat center center/cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Overlay untuk meredupkan background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 10;
            max-width: 500px;
            width: 90%;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            /* Latar belakang card login abu-abu muda */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-img {
            /* Sesuaikan dengan logo Nasrotul Ummah */
            width: 80px;
            height: auto;
            /* Placeholder untuk logo jika gambar logo tidak ada */
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="40" fill="%231A061F">NU</text></svg>');
            background-size: cover;
            display: block;
            margin: 0 auto 5px;
        }

        .logo h3 {
            font-family: 'Scheherazade', serif;
            color: #1A061F;
            font-weight: bold;
        }

        /* Toggle User/Admin */
        .role-toggle {
            display: flex;
            background-color: #e9ecef;
            border-radius: 8px;
            margin-bottom: 25px;
            padding: 5px;
        }

        .role-button {
            flex: 1;
            text-align: center;
            padding: 8px 15px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .role-button.active {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Tombol Login */
        .btn-login {
            background-color: #ffc107;
            /* Warna kuning/emas sesuai gambar */
            border: none;
            color: #1A061F;
            font-weight: bold;
            padding: 10px 0;
            width: 100%;
            margin-top: 15px;
        }

        /* Tautan */
        .forgot-password-link,
        .register-link-container {
            font-size: 0.9rem;
            color: #ffc107;
            text-decoration: none;
            display: block;
            /* Agar bisa disembunyikan */
        }

        .register-link {
            color: #1A061F;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Tombol Modal */
        .btn-modal-primary {
            background-color: #ffc107;
            color: #1A061F;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="logo">
            <div class="logo-img"></div>
            <h3 class="mb-0">NASROTUL UMMAH</h3>
        </div>

        <div class="role-toggle">
            <div class="role-button active" id="user-role">User</div>
            <div class="role-button" id="admin-role">Administrator</div>
        </div>

       <form method="POST" action="{{ url('/login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label small">Email</label>
        <input
            type="email"
            name="email"
            class="form-control"
            placeholder="masukkan email anda"
            required
        >
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label small">Password</label>
        <input
            type="password"
            name="password"
            class="form-control"
            placeholder="masukkan password anda"
            required
        >
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Ingat saya</label>
    </div>

    <button type="submit" class="btn btn-login w-100">
        Login
    </button>
</form>

        <div class="text-center mt-3" id="forgotPasswordContainer">
            <a href="#" class="forgot-password-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Lupa
                password?</a>
        </div>
        <div class="text-center mt-2 small text-muted" id="registerLinkContainer">
            Belum punya akun? <a href="#" class="register-link" data-bs-toggle="modal"
                data-bs-target="#registerModal">Daftar sekarang</a>
        </div>

    </div>

    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Atur Ulang Kata Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="resetUsername" class="form-label">Username / Email</label>
                            <input type="text" class="form-control" id="resetUsername"
                                placeholder="Masukkan Username atau Email Anda" required>
                        </div>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="currentPassword"
                                placeholder="Masukkan password saat ini" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Password Terbaru</label>
                            <input type="password" class="form-control" id="newPassword"
                                placeholder="Masukkan password terbaru" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-modal-primary">Simpan Password Baru</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Daftar Akun Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input
            type="text"
            name="name"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input
            type="email"
            name="email"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input
            type="password"
            name="password"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            required
        >
    </div>

    <button type="submit" class="btn btn-modal-primary">
        Daftar
    </button>
</form>

                </div>
                
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const userBtn = document.getElementById('user-role');
        const adminBtn = document.getElementById('admin-role');
        const loginBtn = document.getElementById('btnLogin');
        const forgotContainer = document.getElementById('forgotPasswordContainer');
        const registerContainer = document.getElementById('registerLinkContainer');

        function setMode(mode) {
            if (mode === 'user') {
                userBtn.classList.add('active');
                adminBtn.classList.remove('active');
                loginBtn.textContent = 'Login sebagai User';
                forgotContainer.style.display = 'block';
                registerContainer.style.display = 'block';
            } else if (mode === 'admin') {
                adminBtn.classList.add('active');
                userBtn.classList.remove('active');
                loginBtn.textContent = 'Login sebagai Administrator';
                forgotContainer.style.display = 'none';
                registerContainer.style.display = 'none';
            }
        }

        // Event Listeners
        userBtn.addEventListener('click', () => setMode('user'));
        adminBtn.addEventListener('click', () => setMode('admin'));

        // Set mode awal saat halaman dimuat (default: User)
        document.addEventListener('DOMContentLoaded', () => setMode('user'));
    </script>
</body>

</html>