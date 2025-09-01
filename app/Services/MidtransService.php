<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;
use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransService
{
    public function __construct()
    {
        $this->initializeConfig();
    }

    private function initializeConfig(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken(Transaction $transaction): array
    {
        try {
            $booking = $transaction->booking;
            $event = $booking->event;
            $user = $booking->user;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->midtrans_order_id,
                    'gross_amount' => $transaction->total_price,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                ],
                'item_details' => [
                    [
                        'id' => $event->id,
                        'price' => $transaction->total_price / $transaction->quantity, // Use calculated unit price
                        'quantity' => $transaction->quantity,
                        'name' => $event->name,
                        'category' => $event->category->name ?? 'Event',
                    ]
                ],
                'callbacks' => [
                    'finish' => config('midtrans.finish_url'),
                    'unfinish' => config('midtrans.unfinish_url'),
                    'error' => config('midtrans.error_url'),
                ],
                'notification_url' => config('midtrans.notification_url'),
                'expiry' => [
                    'unit' => config('midtrans.custom_expiry.unit'),
                    'duration' => config('midtrans.custom_expiry.duration'),
                ],
                'enabled_payments' => config('midtrans.enabled_payments'),
            ];

            // Log the parameters for debugging
            Log::info('Midtrans Snap token parameters', [
                'transaction_id' => $transaction->id,
                'gross_amount' => $transaction->total_price,
                'params' => $params,
            ]);

            // Add credit card configuration if enabled
            if (in_array('credit_card', config('midtrans.enabled_payments'))) {
                $params['credit_card'] = config('midtrans.credit_card');
            }

            $snapToken = Snap::getSnapToken($params);

            // Update transaction with snap token
            $transaction->update(['snap_token' => $snapToken]);

            Log::info('Snap token created successfully', [
                'transaction_id' => $transaction->id,
                'order_id' => $transaction->midtrans_order_id,
            ]);

            return [
                'snap_token' => $snapToken,
                'transaction' => $transaction,
            ];

        } catch (Exception $e) {
            Log::error('Failed to create snap token', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            throw new Exception('Failed to create payment token: ' . $e->getMessage());
        }
    }

    public function getTransactionStatus(string $orderId): array
    {
        try {
            $status = MidtransTransaction::status($orderId);
            
            // Convert object to array if needed
            $statusArray = is_object($status) ? json_decode(json_encode($status), true) : $status;
            
            Log::info('Transaction status retrieved', [
                'order_id' => $orderId,
                'status' => $statusArray['transaction_status'] ?? 'unknown',
            ]);

            return $statusArray;

        } catch (Exception $e) {
            Log::error('Failed to get transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            throw new Exception('Failed to get transaction status: ' . $e->getMessage());
        }
    }

    public function handleNotification(array $notification): Transaction
    {
        try {
            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? null;
            $paymentType = $notification['payment_type'] ?? null;
            $grossAmount = $notification['gross_amount'] ?? null;

            Log::info('Processing Midtrans notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'full_notification' => $notification,
            ]);

            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

            if (!$transaction) {
                Log::error("Transaction not found for order ID: {$orderId}");
                throw new Exception("Transaction not found for order ID: {$orderId}");
            }

            Log::info('Found transaction', [
                'transaction_id' => $transaction->id,
                'current_status' => $transaction->status,
                'booking_id' => $transaction->booking_id,
            ]);

            // Update transaction based on status
            $newStatus = $this->mapMidtransStatus($transactionStatus, $fraudStatus);
            
            Log::info('Status mapping', [
                'midtrans_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'mapped_status' => $newStatus,
            ]);
            
            $updateData = [
                'status' => $newStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'gross_amount' => $grossAmount,
                'midtrans_response' => $notification,
            ];

            if ($newStatus === 'paid') {
                $updateData['paid_at'] = now();
            }

            $transaction->update($updateData);

            Log::info('Transaction updated', [
                'transaction_id' => $transaction->id,
                'new_status' => $newStatus,
            ]);

            // Update booking status if payment is successful
            $booking = $transaction->booking;
            if ($newStatus === 'paid') {
                $booking->update(['status' => 'paid']);
                Log::info('Booking marked as paid', [
                    'booking_id' => $booking->ulid,
                    'booking_status' => 'paid',
                ]);
            } elseif (in_array($newStatus, ['failed', 'expired', 'cancelled'])) {
                $booking->update(['status' => 'cancelled']);
                Log::info('Booking cancelled', [
                    'booking_id' => $booking->ulid,
                    'booking_status' => 'cancelled',
                    'reason' => $newStatus,
                ]);
            }

            Log::info('Transaction notification processed successfully', [
                'transaction_id' => $transaction->id,
                'order_id' => $orderId,
                'old_status' => $transaction->getOriginal('status'),
                'new_status' => $newStatus,
                'booking_status' => $booking->status,
            ]);

            return $transaction;

        } catch (Exception $e) {
            Log::error('Failed to handle notification', [
                'notification' => $notification,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    private function mapMidtransStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        switch ($transactionStatus) {
            case 'capture':
                return ($fraudStatus === 'challenge') ? 'pending' : 'paid';
            case 'settlement':
                return 'paid';
            case 'pending':
                return 'pending';
            case 'deny':
            case 'cancel':
                return 'failed';
            case 'expire':
                return 'expired';
            default:
                return 'pending';
        }
    }

    public function mapMidtransStatusPublic(string $transactionStatus, ?string $fraudStatus): string
    {
        return $this->mapMidtransStatus($transactionStatus, $fraudStatus);
    }

    public function cancelTransaction(string $orderId): bool
    {
        try {
            MidtransTransaction::cancel($orderId);
            
            Log::info('Transaction cancelled via Midtrans', [
                'order_id' => $orderId,
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Failed to cancel transaction', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
