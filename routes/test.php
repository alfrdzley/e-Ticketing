<?php

use App\Models\Booking;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/test-payment', function (CheckoutService $checkoutService) {
    try {
        // Find or create a test booking
        $user = User::first();
        $event = Event::first();

        if (! $user || ! $event) {
            return response()->json(['error' => 'No user or event found for testing']);
        }

        $booking = Booking::firstOrCreate([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ], [
            'quantity' => 1,
            'total_price' => 50000,
            'status' => 'pending',
            'attendee_name' => $user->name,
            'attendee_email' => $user->email,
            'attendee_phone' => $user->phone ?? '081234567890',
        ]);

        // Process checkout
        $result = $checkoutService->processCheckout($booking);

        return response()->json([
            'success' => true,
            'booking_id' => $booking->ulid,
            'transaction_id' => $result['transaction']->id,
            'snap_token' => $result['snap_token'],
            'client_key' => config('midtrans.client_key'),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => config('app.debug') ? $e->getTraceAsString() : null,
        ], 500);
    }
});

// Test route untuk simulasi notification Midtrans
Route::post('/test-midtrans-notification/{transaction}', function (Request $request, Transaction $transaction) {
    try {
        $midtransService = new MidtransService;

        // Simulasi notification data
        $notificationData = [
            'order_id' => $transaction->midtrans_order_id,
            'transaction_status' => $request->get('status', 'settlement'),
            'payment_type' => $request->get('payment_type', 'bank_transfer'),
            'gross_amount' => $transaction->total_price,
            'transaction_time' => now()->toISOString(),
            'currency' => 'IDR',
            'fraud_status' => $request->get('fraud_status', 'accept'),
        ];

        Log::info('Testing Midtrans notification', $notificationData);

        $result = $midtransService->handleNotification($notificationData);

        return response()->json([
            'success' => true,
            'message' => 'Notification processed successfully',
            'transaction' => $result,
            'booking_status' => $result->booking->status,
        ]);

    } catch (\Exception $e) {
        Log::error('Test notification failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
})->name('test.midtrans-notification');

// Test route untuk melihat status transaction
Route::get('/test-transaction-status/{transaction}', function (Transaction $transaction) {
    return response()->json([
        'transaction' => $transaction,
        'booking' => $transaction->booking,
        'transaction_status' => $transaction->status,
        'booking_status' => $transaction->booking->status,
        'is_paid' => $transaction->isPaid(),
    ]);
})->name('test.transaction-status');
