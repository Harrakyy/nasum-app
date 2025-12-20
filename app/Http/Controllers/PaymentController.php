<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;
    
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    
   
    public function createPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'sometimes|string',
        ]);
        
        try {
            
            $transaction = $this->midtransService->createTransaction($order, [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? $request->phone,
            ]);
            
            return response()->json([
                'success' => true,
                'snap_token' => $transaction['snap_token'],
                'order_id' => $transaction['order_id'],
                'client_key' => config('services.midtrans.client_key'),
                'payment' => $transaction['payment'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    public function showPayment($orderId)
    {
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return view('payment.show', [
            'payment' => $payment,
            'client_key' => config('services.midtrans.client_key'),
        ]);
    }
    
       public function handleNotification(Request $request)
    {
        $notification = $request->all();
        
        Log::info('Midtrans Notification Received:', $notification);
      
        $result = $this->midtransService->handleNotification($notification);
        
        if ($result['status'] === 'success') {
            return response()->json(['status' => 'success']);
        }
        
        return response()->json(['status' => 'error'], 400);
    }
    
   
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        
        if ($payment->status === 'pending') {
            $status = $this->midtransService->checkStatus($orderId);
          
        }
        
        return view('payment.success', [
            'payment' => $payment,
        ]);
    }
    
  
    public function failed(Request $request)
    {
        $orderId = $request->query('order_id');
        
        $payment = Payment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return view('payment.failed', [
            'payment' => $payment,
        ]);
    }
    
 
    public function checkStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);
        
        $status = $this->midtransService->checkStatus($request->order_id);
        
        return response()->json([
            'success' => true,
            'status' => $status,
        ]);
    }
    
    
    public function history()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('payment.history', [
            'payments' => $payments,
        ]);
    }
}