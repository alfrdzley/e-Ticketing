<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Booking;
use App\Models\Event;
use App\Services\QRCodeService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected $qrService;
    protected $bookingService;

    public function __construct(QRCodeService $qrService, BookingService $bookingService)
    {
        $this->qrService = $qrService;
        $this->bookingService = $bookingService;
    }

    /**
     * Show payment instructions page
     */
    public function instructions(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking is not pending payment.');
        }

        $event = $booking->event;

        // Generate QR code if not exists
        if (!$event->payment_qr_code) {
            try {
                $qrPath = $this->qrService->generateEventPaymentQR($event);
                $event->update(['payment_qr_code' => $qrPath]);
            } catch (Exception $e) {
                // Handle error gracefully
                $qrPath = null;
            }
        }

        return view('bookings.payment', compact('booking', 'event'));
    }

    /**
     * Upload payment proof
     */
    public function uploadProof(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'payment_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'This booking is not pending payment.');
        }

        // Store payment proof
        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        $booking->update([
            'payment_proof_path' => $proofPath,
            'payment_reference' => $request->payment_reference,
            'notes' => $request->notes ? $booking->notes . "\n" . $request->notes : $booking->notes,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Payment proof uploaded successfully. We will verify your payment shortly.');
    }

    /**
     * Admin: Verify payment
     */
    public function verify(Request $request, Booking $booking)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'This booking is not pending verification.');
        }

        if ($request->action === 'approve') {
            $this->bookingService->confirmBooking($booking, [
                'payment_method' => 'bank_transfer',
                'payment_reference' => $booking->payment_reference,
                'payment_proof_path' => $booking->payment_proof_path,
            ]);

            $message = 'Payment approved and booking confirmed.';
        } else {
            $booking->update([
                'status' => 'rejected',
                'notes' => $booking->notes . "\nRejected: " . $request->admin_notes
            ]);

            $message = 'Payment rejected.';
        }

        return back()->with('success', $message);
    }

    /**
     * Generate payment QR for event
     */
    public function generateQR(Event $event)
    {
        try {
            $qrPath = $this->qrService->generateEventPaymentQR($event);
            $event->update(['payment_qr_code' => $qrPath]);

            return response()->json([
                'success' => true,
                'qr_path' => Storage::url($qrPath)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
