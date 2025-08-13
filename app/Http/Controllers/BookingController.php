<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Booking;
use App\Models\Event;
use App\Services\BookingService;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected $bookingService;
    protected $qrService;

    public function __construct(BookingService $bookingService, QRCodeService $qrService)
    {
        $this->bookingService = $bookingService;
        $this->qrService = $qrService;
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request, Event $event)
    {
        $user = Auth::user();
        Log::info('Booking store method called', [
            'user_id' => $user->id, 
            'event_id' => $event->id, 
            'request_data' => $request->all()
        ]);
        
        Log::info('Event found', ['event_id' => $event->id, 'event_name' => $event->name]);
        
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($event->status !== 'published') {
            Log::warning('Event not published', ['event_status' => $event->status]);
            return redirect()->back()
                ->with('error', 'Event ini tidak tersedia untuk booking.')
                ->withInput();
        }

        try {
            Log::info('Creating booking via service');
            $booking = $this->bookingService->createBooking($event, [
                'user_id' => $user->id,
                'quantity' => $request->quantity,
                'booker_name' => $request->name,
                'booker_email' => $request->email,
                'booker_phone' => $request->phone,
            ]);

            Log::info('Booking created successfully', ['booking_id' => $booking->id, 'status' => $booking->status]);

            if ($booking->status === 'paid') {
                // Free event - redirect to ticket page directly
                Log::info('Free event booking - redirecting to ticket');
                return redirect()->route('tickets.show', $booking)
                    ->with('success', 'Booking berhasil dikonfirmasi! Tiket digital Anda sudah siap.');
            } else {
                // Paid event - redirect to payment page
                Log::info('Paid event booking - redirecting to payment');
                return redirect()->route('bookings.payment', $booking)
                    ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran dalam 24 jam untuk mengkonfirmasi tiket Anda.');
            }
        } catch (Exception $e) {
            Log::error('Booking creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to booking.');
        }
        
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show payment page
     */
    public function payment(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to booking.');
        }
        
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking is not pending payment.');
        }

        $event = $booking->event;

        // Generate QR code specific for this booking
        $paymentQRPath = null;
        if ($event->payment_account_number) {
            try {
                Log::info('Generating payment QR code for booking', ['booking_id' => $booking->id]);
                $paymentQRPath = $this->qrService->generateBookingPaymentQR($booking);
                Log::info('Payment QR code generated successfully', ['path' => $paymentQRPath]);
            } catch (Exception $e) {
                Log::error('Failed to generate payment QR code', ['error' => $e->getMessage()]);
                // Continue without QR code
            }
        }

        return view('bookings.payment', compact('booking', 'event', 'paymentQRPath'));
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
            ->with('success', 'Bukti pembayaran berhasil diupload. Kami akan memverifikasi pembayaran Anda segera.');
    }

    /**
     * Confirm booking (admin only)
     */
    public function confirm(Request $request, Booking $booking)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'This booking is not pending verification.');
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
                    'notes' => $booking->notes . "\nDitolak: " . $request->admin_notes
                ]);

                $message = 'Pembayaran ditolak.';
            }

            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
} 