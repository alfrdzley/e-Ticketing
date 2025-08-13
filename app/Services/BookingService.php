<?php

namespace App\Services;

use Exception;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingService
{
    protected $qrService;

    public function __construct(QRCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Create a new booking
     */
    public function createBooking(Event $event, array $bookingData): Booking
    {
        // Validate quota
        if (!$event->hasAvailableQuota($bookingData['quantity'])) {
            throw new Exception('Not enough quota available');
        }

        // Calculate pricing
        $pricing = $this->calculateTotal($event, $bookingData['quantity'], $bookingData['discount_code'] ?? null);
        
        // Generate unique booking code
        $bookingCode = $this->generateBookingCode();

        // Set expiration (24 hours for paid events, immediately confirmed for free events)
        $expiresAt = $pricing['final_amount'] > 0 ? now()->addHours(24) : null;
        $status = $pricing['final_amount'] > 0 ? 'pending' : 'paid';

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $bookingData['user_id'] ?? null,
            'event_id' => $event->id,
            'quantity' => $bookingData['quantity'],
            'unit_price' => $pricing['unit_price'],
            'total_amount' => $pricing['total_amount'],
            'discount_amount' => $pricing['discount_amount'],
            'final_amount' => $pricing['final_amount'],
            'status' => $status,
            'booking_date' => now(),
            'expired_at' => $expiresAt,
            'booker_name' => $bookingData['booker_name'],
            'booker_email' => $bookingData['booker_email'],
            'booker_phone' => $bookingData['booker_phone'] ?? null,
            'discount_code_id' => $bookingData['discount_code_id'] ?? null,
        ]);

        // Generate tickets for this booking
        $this->generateTickets($booking);

        // Generate ticket QR for paid bookings or immediately for free events
        if ($status === 'paid') {
            $this->generateTicketQR($booking);
        }

        return $booking;
    }

    /**
     * Calculate booking total
     */
    public function calculateTotal(Event $event, int $quantity, $discountCode = null): array
    {
        $unitPrice = $event->price;
        $totalAmount = $unitPrice * $quantity;
        $discountAmount = 0;

        // Apply discount if provided
        if ($discountCode) {
            // Implement discount logic here
            // $discountAmount = ...
        }

        $finalAmount = $totalAmount - $discountAmount;

        return [
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
        ];
    }

    /**
     * Confirm booking payment
     */
    public function confirmBooking(Booking $booking, array $paymentData): bool
    {
        if ($booking->status !== 'pending') {
            throw new Exception('Booking is not in pending status');
        }

        if ($booking->expired_at && $booking->expired_at->isPast()) {
            $this->expireBooking($booking);
            throw new Exception('Booking has expired');
        }

        $booking->update([
            'status' => 'paid',
            'payment_method' => $paymentData['payment_method'] ?? 'bank_transfer',
            'payment_reference' => $paymentData['payment_reference'] ?? null,
            'payment_date' => now(),
            'payment_proof_path' => $paymentData['payment_proof_path'] ?? null,
        ]);

        // Generate tickets if not already generated
        if ($booking->tickets()->count() === 0) {
            $this->generateTickets($booking);
        }

        // Generate ticket QR
        $this->generateTicketQR($booking);

        return true;
    }

    /**
     * Generate tickets for booking
     */
    public function generateTickets(Booking $booking): void
    {
        // Generate tickets based on quantity
        for ($i = 1; $i <= $booking->quantity; $i++) {
            $ticketCode = $this->generateTicketCode();
            
            Ticket::create([
                'ticket_code' => $ticketCode,
                'booking_id' => $booking->id,
                'attendee_name' => $booking->booker_name,
                'attendee_email' => $booking->booker_email,
                'attendee_phone' => $booking->booker_phone,
                'seat_number' => null, // Can be assigned later
                'special_requirements' => null,
                'is_checked_in' => false,
                'checked_in_at' => null,
                'checked_in_by' => null,
                'is_cancelled' => false,
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ]);
        }
    }

    /**
     * Generate unique ticket code
     */
    private function generateTicketCode(): string
    {
        do {
            $code = 'TICKET-' . strtoupper(Str::random(10));
        } while (Ticket::where('ticket_code', $code)->exists());

        return $code;
    }

    /**
     * Expire booking
     */
    public function expireBooking(Booking $booking): bool
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'expired']);
            return true;
        }

        return false;
    }

    /**
     * Generate ticket QR code
     */
    public function generateTicketQR(Booking $booking): string
    {
        $qrPath = $this->qrService->generateTicketQR($booking->booking_code, $booking->event_id);
        
        $booking->update(['ticket_qr_code_path' => $qrPath]);
        
        return $qrPath;
    }

    /**
     * Generate unique booking code
     */
    private function generateBookingCode(): string
    {
        do {
            $code = 'BK' . date('ymd') . strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Validate ticket for entry
     */
    public function validateTicket(string $bookingCode, string $validatedBy = null, string $entryGate = null): bool
    {
        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (!$booking || !$booking->canBeValidated()) {
            return false;
        }

        $booking->update(['entry_validated_at' => now()]);

        // Log validation
        $booking->ticketValidations()->create([
            'validated_at' => now(),
            'validated_by' => $validatedBy,
            'entry_gate' => $entryGate,
        ]);

        return true;
    }
}
