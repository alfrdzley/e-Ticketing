<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use App\Services\CheckoutService;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $checkoutService;

    protected $midtransService;

    public function __construct(CheckoutService $checkoutService, MidtransService $midtransService)
    {
        $this->checkoutService = $checkoutService;
        $this->midtransService = $midtransService;
    }

    public function checkout(Booking $booking): JsonResponse
    {
        try {
            $result = $this->checkoutService->processCheckout($booking);

            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token'],
                'transaction' => $result['transaction'],
                'client_key' => config('midtrans.client_key'),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment checkout failed', [
                'booking_id' => $booking->ulid,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function notification(Request $request): JsonResponse
    {
        try {
            $notification = $request->all();

            Log::info('Midtrans notification received', $notification);

            $transaction = $this->midtransService->handleNotification($notification);

            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully',
                'transaction_status' => $transaction->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process Midtrans notification', [
                'notification' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process notification',
            ], 400);
        }
    }

    public function status(Transaction $transaction): JsonResponse
    {
        try {
            $midtransStatus = $this->midtransService->getTransactionStatus($transaction->midtrans_order_id);

            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                'midtrans_status' => $midtransStatus,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get payment status', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment status',
            ], 400);
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');
        $statusCode = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');

        Log::info('Payment finish callback', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus,
        ]);

        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

        if (! $transaction) {
            return redirect()->route('events.index')->with('error', 'Transaction not found');
        }

        // Try to get latest status from Midtrans and update if needed
        try {
            $midtransStatus = $this->midtransService->getTransactionStatus($orderId);

            // Update status based on latest Midtrans response
            if (isset($midtransStatus['transaction_status'])) {
                $latestStatus = $this->midtransService->mapMidtransStatusPublic(
                    $midtransStatus['transaction_status'],
                    $midtransStatus['fraud_status'] ?? null
                );

                if ($latestStatus === 'paid' && $transaction->status !== 'paid') {
                    $transaction->update(['status' => 'paid', 'paid_at' => now()]);
                    $transaction->booking->update(['status' => 'paid']);

                    Log::info('Updated transaction status on finish callback', [
                        'transaction_id' => $transaction->id,
                        'new_status' => 'paid',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to check Midtrans status on finish callback', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
        }

        // Refresh transaction data
        $transaction->refresh();
        $transaction->booking->refresh();

        if ($transaction->isPaid()) {
            return redirect()->route('bookings.show', $transaction->booking)
                ->with('success', 'Payment successful! Your booking has been confirmed.');
        }

        return redirect()->route('bookings.show', $transaction->booking)
            ->with('warning', 'Payment is being processed. Please wait for confirmation.');
    }

    public function unfinish(Request $request)
    {
        $orderId = $request->query('order_id');

        Log::info('Payment unfinish callback', ['order_id' => $orderId]);

        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

        if ($transaction) {
            return redirect()->route('bookings.show', $transaction->booking)
                ->with('warning', 'Payment was not completed. Please try again.');
        }

        return redirect()->route('events.index')
            ->with('error', 'Transaction not found');
    }

    public function error(Request $request)
    {
        $orderId = $request->query('order_id');

        Log::error('Payment error callback', ['order_id' => $orderId]);

        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

        if ($transaction) {
            return redirect()->route('bookings.show', $transaction->booking)
                ->with('error', 'Payment failed. Please try again or contact support.');
        }

        return redirect()->route('events.index')
            ->with('error', 'Payment error occurred');
    }

    /**
     * Legacy method for backward compatibility
     */
    public function legacyCheckout(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->checkoutService->processCheckoutFromRequest($request->all());

            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token'],
                'transaction' => $result['transaction'],
                'client_key' => config('midtrans.client_key'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
