<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - {{ config('app.name') }}</title>


    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ $client_key }}">
</script>
</head>
<body>

<h2>Pembayaran</h2>

<p><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
<p><strong>Total:</strong> Rp {{ number_format($booking->total_price,0,',','.') }}</p>
<p><strong>Status:</strong> {{ $booking->payment_status }}</p>

<button id="pay-button">Bayar Sekarang</button>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function (result) {
            alert('Pembayaran berhasil');
            console.log(result);
        },
        onPending: function (result) {
            alert('Menunggu pembayaran');
            console.log(result);
        },
        onError: function (result) {
            alert('Pembayaran gagal');
            console.log(result);
        }
    });
});

</script>

</body>
</html>
