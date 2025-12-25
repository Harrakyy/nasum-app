<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan | Nasrotul Ummah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style3.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">Nasrotul Ummah</a>

        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
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
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5 mt-5">

    <h4 class="mb-4 fw-bold">Detail Pemesanan</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-borderless">
                <tr>
                    <th width="30%">Kode Booking</th>
                    <td>{{ $booking->booking_code }}</td>
                </tr>

                <tr>
                    <th>Nama Paket</th>
                    <td>{{ $booking->package->name ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Tanggal Keberangkatan</th>
                    <td>{{ $booking->packageDate->display_date ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Jumlah Jamaah</th>
                    <td>{{ $booking->jumlah_jamaah }} orang</td>
                </tr>

                <tr>
                    <th>Total Pembayaran</th>
                    <td class="fw-bold text-success">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Status Pembayaran</th>
                    <td>
                        @if(in_array($booking->payment_status, ['paid', 'settlement']))
                            <span class="badge bg-success">Lunas</span>
                        @elseif($booking->payment_status === 'pending')
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        @elseif(in_array($booking->payment_status, ['expire', 'cancel', 'deny']))
                            <span class="badge bg-danger">Gagal</span>
                        @else
                            <span class="badge bg-secondary">Belum Bayar</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ strtoupper($booking->payment_method ?? '-') }}</td>
                </tr>

                <tr>
                    <th>Tanggal Pembayaran</th>
                    <td>
                        {{ $booking->payment_date
                            ? $booking->payment_date->format('d M Y H:i')
                            : '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Bukti Pembayaran</th>
                    <td>
                        @if($booking->payment_proof)
                            <code>{{ $booking->payment_proof }}</code>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>

            <div class="mt-4">
                @if($booking->payment_status === 'pending')
                    <a href="{{ route('booking.confirmation', $booking->id) }}"
                       class="btn btn-success">
                        Bayar Sekarang
                    </a>
                @endif

                <a href="{{ route('my.umrah') }}"
                   class="btn btn-secondary ms-2">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
