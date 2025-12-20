<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;
    
    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = config('services.midtrans.is_production');
        
        // Set Midtrans configuration
        Config::$serverKey = $this->serverKey;
        Config::$clientKey = $this->clientKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    
    /**
     * Create Snap transaction
     */
    public function createTransaction($order, array $customerDetails = [])
    {
        try {
            // Generate order ID
            $orderId = 'ORDER-' . Str::random(10) . '-' . time();
            
            // Set transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => $order->total_amount,
            ];
            
            // Set item details
            $itemDetails = [];
            
            // Jika order memiliki items sebagai array
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    $itemDetails[] = [
                        'id' => $item['id'] ?? rand(),
                        'price' => $item['price'] ?? $order->total_amount,
                        'quantity' => $item['quantity'] ?? 1,
                        'name' => $item['name'] ?? 'Product',
                    ];
                }
            } else {
                // Default item jika tidak ada items
                $itemDetails[] = [
                    'id' => $order->id,
                    'price' => $order->total_amount,
                    'quantity' => 1,
                    'name' => 'Order #' . $order->id,
                ];
            }
            
            // Set customer details
            $customerDetails = [
                'first_name' => $customerDetails['first_name'] ?? ($order->customer_name ?? 'Customer'),
                'last_name' => $customerDetails['last_name'] ?? '',
                'email' => $customerDetails['email'] ?? ($order->customer_email ?? 'customer@example.com'),
                'phone' => $customerDetails['phone'] ?? ($order->customer_phone ?? '081234567890'),
                'billing_address' => $customerDetails['billing_address'] ?? ($order->shipping_address ?? ''),
                'shipping_address' => $customerDetails['shipping_address'] ?? ($order->shipping_address ?? ''),
            ];
            
            // Set expiry
            $expiry = [
                'start_time' => date("Y-m-d H:i:s O", time()),
                'unit' => 'minutes',
                'duration' => 1440, // 24 jam
            ];
            
            // Create transaction parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'expiry' => $expiry,
            ];
            
            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // Save payment record
            $payment = Payment::create([
                'order_id' => $orderId,
                'user_id' => $order->user_id ?? auth()->id(),
                'amount' => $order->total_amount,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addHours(24),
            ]);
            
            // Update order with payment ID jika order adalah model
            if ($order instanceof \Illuminate\Database\Eloquent\Model) {
                $order->update([
                    'payment_id' => $payment->id,
                ]);
            }
            
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'client_key' => $this->clientKey,
                'payment' => $payment,
            ];
            
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Handle notification from Midtrans
     */
    public function handleNotification(array $notification)
    {
        $orderId = $notification['order_id'] ?? null;
        $statusCode = $notification['status_code'] ?? null;
        $grossAmount = $notification['gross_amount'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? null;
        
        if (!$orderId || !$transactionStatus) {
            Log::error('Invalid notification data', $notification);
            return ['status' => 'error', 'message' => 'Invalid notification data'];
        }
        
        // Cari payment berdasarkan order_id
        $payment = Payment::where('order_id', $orderId)->first();
        
        if (!$payment) {
            Log::error('Payment not found for order_id: ' . $orderId);
            return ['status' => 'error', 'message' => 'Payment not found'];
        }
        
        // Verifikasi signature key
        $serverKey = $this->serverKey;
        $signatureKey = $notification['signature_key'] ?? '';
        
        $expectedSignature = hash('sha512', 
            $orderId . $statusCode . $grossAmount . $serverKey
        );
        
        if ($signatureKey !== $expectedSignature) {
            Log::warning('Invalid signature for order_id: ' . $orderId, [
                'expected' => $expectedSignature,
                'received' => $signatureKey
            ]);
            return ['status' => 'error', 'message' => 'Invalid signature'];
        }
        
        // Update payment status
        $paymentStatus = $this->mapStatus($transactionStatus, $notification['fraud_status'] ?? null);
        
        $paymentData = [
            'status' => $paymentStatus,
            'transaction_id' => $notification['transaction_id'] ?? null,
            'payment_type' => $notification['payment_type'] ?? null,
            'payment_data' => $notification,
        ];
        
        // Simpan VA number jika ada
        if (isset($notification['va_numbers'][0])) {
            $paymentData['bank'] = $notification['va_numbers'][0]['bank'] ?? null;
            $paymentData['va_number'] = $notification['va_numbers'][0]['va_number'] ?? null;
        }
        
        $payment->update($paymentData);
        
        // Update order status
        if ($payment->order) {
            $orderStatus = $this->mapOrderStatus($paymentStatus);
            $payment->order->update(['status' => $orderStatus]);
        }
        
        Log::info('Payment updated', [
            'order_id' => $orderId,
            'status' => $paymentStatus
        ]);
        
        return ['status' => 'success', 'payment' => $payment];
    }
    
    /**
     * Map Midtrans status to our status
     */
    private function mapStatus($transactionStatus, $fraudStatus)
    {
        switch ($transactionStatus) {
            case 'capture':
                return ($fraudStatus == 'accept') ? 'success' : 'failed';
            case 'settlement':
                return 'success';
            case 'pending':
                return 'pending';
            case 'deny':
            case 'cancel':
            case 'expire':
                return 'failed';
            default:
                return 'pending';
        }
    }
    
    /**
     * Map payment status to order status
     */
    private function mapOrderStatus($paymentStatus)
    {
        switch ($paymentStatus) {
            case 'success':
                return 'paid';
            case 'failed':
                return 'cancelled';
            default:
                return 'pending';
        }
    }
    
    /**
     * Get transaction status
     */
    public function checkStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (\Exception $e) {
            Log::error('Check status error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Cancel transaction
     */
    public function cancelTransaction($orderId)
    {
        try {
            $result = Transaction::cancel($orderId);
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Cancel transaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}