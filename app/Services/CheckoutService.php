<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function processCheckout(Booking $booking): array
    {
        try {
            return DB::transaction(function () use ($booking) {
                // Validate booking
                if (! $booking->event) {
                    throw new Exception('Event not found for this booking');
                }

                if ($booking->status !== 'pending') {
                    throw new Exception('Booking is not in pending status');
                }

                // Use the booking's final amount as the total price
                $totalPrice = $booking->final_amount;

                // Validate amount
                if ($totalPrice <= 0) {
                    throw new Exception('Invalid booking amount: '.$totalPrice);
                }

                // Create transaction
                $transaction = Transaction::create([
                    'booking_id' => $booking->ulid,
                    'quantity' => $booking->quantity,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                Log::info('Transaction created', [
                    'transaction_id' => $transaction->id,
                    'booking_id' => $booking->ulid,
                    'amount' => $totalPrice,
                ]);

                // Create Midtrans snap token
                $result = $this->midtransService->createSnapToken($transaction);

                return $result;
            });

        } catch (Exception $e) {
            Log::error('Checkout process failed', [
                'booking_id' => $booking->ulid,
                'error' => $e->getMessage(),
            ]);

            throw new Exception('Checkout failed: '.$e->getMessage());
        }
    }

    public function processCheckoutFromRequest(array $data): array
    {
        try {
            // Legacy method for backward compatibility
            // This handles the old checkout process with direct data

            $booking = Booking::where('ulid', $data['booking_id'] ?? null)->first();

            if (! $booking) {
                throw new Exception('Booking not found');
            }

            return $this->processCheckout($booking);

        } catch (Exception $e) {
            Log::error('Legacy checkout process failed', [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            throw new Exception('Checkout failed: '.$e->getMessage());
        }
    }
}
