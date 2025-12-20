<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - {{ config('app.name') }}</title>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $client_key }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Pembayaran</h1>
                <p class="text-gray-600">Selesaikan pembayaran untuk pesanan Anda</p>
            </div>
            
            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Detail Pesanan</h2>
                    <div class="mt-2 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium">{{ $payment->order_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium capitalize px-2 py-1 rounded 
                                {{ $payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($payment->status == 'success' ? 'bg-green-100 text-green-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Batas Waktu:</span>
                            <span class="font-medium">{{ $payment->expired_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Button -->
                <div class="mt-6">
                    <button id="pay-button" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium 
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   focus:ring-offset-2 transition-colors">
                        Bayar Sekarang
                    </button>
                </div>
                
                <!-- Payment Instructions (akan muncul setelah memilih metode) -->
                <div id="payment-instructions" class="mt-6 hidden">
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Instruksi Pembayaran:</h3>
                        <div id="instructions-content"></div>
                    </div>
                </div>
            </div>
            
            <!-- Info -->
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Pembayaran aman dan terenkripsi melalui Midtrans. 
                            Anda akan diarahkan ke halaman pembayaran yang aman.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Get snap token from server
        async function getSnapToken() {
            try {
                const response = await fetch('{{ route("api.payment.create", $payment->order) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                });
                
                const data = await response.json();
                
                if (data.success) {
                    return data.snap_token;
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memproses pembayaran. Silakan coba lagi.');
                return null;
            }
        }
        
        // Handle payment
        document.getElementById('pay-button').addEventListener('click', async function() {
            const button = this;
            const originalText = button.textContent;
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span> Memproses...';
            
            try {
                // Get snap token
                const snapToken = await getSnapToken();
                
                if (snapToken) {
                    // Open Midtrans snap popup
                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            window.location.href = '{{ route("payment.success") }}?order_id={{ $payment->order_id }}';
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            // Show payment instructions
                            showPaymentInstructions(result);
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            window.location.href = '{{ route("payment.failed") }}?order_id={{ $payment->order_id }}';
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            // Reset button
                            button.disabled = false;
                            button.textContent = originalText;
                        }
                    });
                }
            } catch (error) {
                console.error('Payment error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                button.disabled = false;
                button.textContent = originalText;
            }
        });
        
        // Show payment instructions
        function showPaymentInstructions(result) {
            const instructionsDiv = document.getElementById('payment-instructions');
            const contentDiv = document.getElementById('instructions-content');
            
            if (result.payment_type === 'bank_transfer') {
                let html = '';
                
                if (result.va_numbers && result.va_numbers.length > 0) {
                    const va = result.va_numbers[0];
                    html = `
                        <div class="bg-gray-50 p-4 rounded">
                            <h4 class="font-medium mb-2">Virtual Account ${va.bank.toUpperCase()}</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-600">Nomor VA:</span>
                                    <div class="flex items-center mt-1">
                                        <code class="bg-white px-3 py-2 rounded border text-lg font-mono">
                                            ${va.va_number}
                                        </code>
                                        <button onclick="copyToClipboard('${va.va_number}')" 
                                                class="ml-2 px-3 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                            Salin
                                        </button>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    Silakan transfer sesuai jumlah ke Virtual Account di atas.
                                    Pembayaran akan diproses otomatis.
                                </p>
                            </div>
                        </div>
                    `;
                }
                
                contentDiv.innerHTML = html;
                instructionsDiv.classList.remove('hidden');
                
                // Check payment status periodically
                checkPaymentStatus();
            }
        }
        
        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Berhasil disalin!');
            });
        }
        
        // Check payment status periodically
        function checkPaymentStatus() {
            const interval = setInterval(async () => {
                try {
                    const response = await fetch('{{ route("api.payment.check-status") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: '{{ $payment->order_id }}'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success && data.status.transaction_status === 'settlement') {
                        clearInterval(interval);
                        window.location.href = '{{ route("payment.success") }}?order_id={{ $payment->order_id }}';
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            }, 5000); // Check every 5 seconds
        }
        
        // Auto-check on page load if payment is pending
        @if($payment->status == 'pending')
        window.addEventListener('load', function() {
            checkPaymentStatus();
        });
        @endif
    </script>
</body>
</html>