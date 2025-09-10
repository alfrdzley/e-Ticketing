<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Services\BookingService;
use App\Services\QRCodeService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $bookingService;

    protected $qrService;

    public function __construct(
        BookingService $bookingService,
        QRCodeService $qrService,
    ) {
        $this->bookingService = $bookingService;
        $this->qrService = $qrService;
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($event->status !== 'published') {
            return redirect()
                ->back()
                ->with('error', 'Event ini tidak tersedia untuk booking.')
                ->withInput();
        }

        try {
            $booking = $this->bookingService->createBooking($event, [
                'user_id' => $user->id,
                'quantity' => $request->quantity,
                'booker_name' => $request->name,
                'booker_email' => $request->email,
                'booker_phone' => $request->phone,
            ]);

            if ($booking->status === 'paid') {
                // Free event - redirect to ticket page directly
                return redirect()
                    ->route('tickets.show', $booking)
                    ->with(
                        'success',
                        'Booking berhasil dikonfirmasi! Tiket digital Anda sudah siap.',
                    );
            } else {
                return redirect()
                    ->route('bookings.payment', $booking)
                    ->with(
                        'success',
                        'Booking berhasil dibuat! Silakan lakukan pembayaran dalam 24 jam untuk mengkonfirmasi tiket Anda.',
                    );
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking): View
    {
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Check if there's a recent transaction that might need status update
        $this->refreshBookingStatusIfNeeded($booking);

        // Refresh booking data after potential status update
        $booking->refresh();

        return view('bookings.show', compact('booking'));
    }

    /**
     * Check and refresh booking status if there's a pending transaction
     */
    private function refreshBookingStatusIfNeeded(Booking $booking): void
    {
        // Only check if booking is still pending
        if ($booking->status !== 'pending') {
            return;
        }

        // Find the latest transaction for this booking
        $latestTransaction = $booking->transactions()
            ->where('status', '!=', 'paid')
            ->where('created_at', '>=', now()->subHours(2)) // Only check recent transactions
            ->latest()
            ->first();

        if (! $latestTransaction || ! $latestTransaction->midtrans_order_id) {
            return;
        }

        try {
            // Get status from Midtrans
            $midtransService = app(\App\Services\MidtransService::class);
            $midtransStatus = $midtransService->getTransactionStatus($latestTransaction->midtrans_order_id);

            Log::info('Checking transaction status on booking view', [
                'booking_id' => $booking->ulid,
                'transaction_id' => $latestTransaction->id,
                'midtrans_status' => $midtransStatus['transaction_status'] ?? 'unknown',
            ]);

            // Update status if payment is successful
            if (isset($midtransStatus['transaction_status'])) {
                $newStatus = $midtransService->mapMidtransStatusPublic(
                    $midtransStatus['transaction_status'],
                    $midtransStatus['fraud_status'] ?? null
                );

                if ($newStatus === 'paid' && $latestTransaction->status !== 'paid') {
                    $latestTransaction->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'midtrans_response' => $midtransStatus,
                    ]);

                    $booking->update(['status' => 'paid']);

                    Log::info('Updated booking status on view refresh', [
                        'booking_id' => $booking->ulid,
                        'transaction_id' => $latestTransaction->id,
                        'new_status' => 'paid',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to refresh booking status', [
                'booking_id' => $booking->ulid,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show payment page
     */
    public function payment(Booking $booking): RedirectResponse|View
    {
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'This booking is not pending payment.');
        }

        $event = $booking->event;

        // Generate QR code specific for this booking
        $paymentQRPath = null;
        if ($event->payment_account_number) {
            try {
                $paymentQRPath = $this->qrService->generateBookingPaymentQR(
                    $booking,
                );
            } catch (Exception $e) {
                Log::error('Failed to generate payment QR code', [
                    'error' => $e->getMessage(),
                ]);
                // Continue without QR code
            }
        }

        return view(
            'bookings.payment',
            compact('booking', 'event', 'paymentQRPath'),
        );
    }

    /**
     * Upload payment proof
     */
    public function uploadProof(
        Request $request,
        Booking $booking,
    ): RedirectResponse {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($booking->status !== 'pending') {
            return back()->with(
                'error',
                'This booking is not pending payment.',
            );
        }

        // Store payment proof
        $proofPath = $request
            ->file('payment_proof')
            ->store('payment-proofs', 'public');

        $booking->update([
            'payment_proof_path' => $proofPath,
            'payment_reference' => $request->payment_reference,
            'notes' => $request->notes
                ? $booking->notes."\n".$request->notes
                : $booking->notes,
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with(
                'success',
                'Bukti pembayaran berhasil diupload. Kami akan memverifikasi pembayaran Anda segera.',
            );
    }

    /**
     * Check booking status via AJAX
     */
    public function statusCheck(Booking $booking): \Illuminate\Http\JsonResponse
    {
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Force refresh status
        $this->refreshBookingStatusIfNeeded($booking);
        $booking->refresh();

        return response()->json([
            'status' => $booking->status,
            'is_paid' => $booking->status === 'paid',
            'updated_at' => $booking->updated_at->toISOString(),
        ]);
    }

    /**
     * Confirm booking (admin only)
     */
    public function confirm(
        Request $request,
        Booking $booking,
    ): RedirectResponse {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($booking->status !== 'pending') {
            return back()->with(
                'error',
                'This booking is not pending verification.',
            );
        }

        try {
            if ($request->action === 'approve') {
                $this->bookingService->confirmBooking($booking, [
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => $booking->payment_reference,
                    'payment_proof_path' => $booking->payment_proof_path,
                ]);

                $message = 'Pembayaran disetujui dan booking dikonfirmasi.';
            } else {
                $booking->update([
                    'status' => 'rejected',
                    'notes' => $booking->notes."\nDitolak: ".$request->admin_notes,
                ]);

                $message = 'Pembayaran ditolak.';
            }

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
