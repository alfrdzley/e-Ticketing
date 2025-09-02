<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    protected $bookingService;
    protected $qrService;

    public function __construct(BookingService $bookingService, QRCodeService $qrService)
    {
        $this->bookingService = $bookingService;
        $this->qrService = $qrService;
    }

    /**
     * Show digital ticket
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to ticket.');
        }

        if (!in_array($booking->status, ['paid', 'confirmed', 'completed'])) {
            abort(404, 'Ticket not found or not yet confirmed.');
        }

        return view('tickets.show', compact('booking'));
    }

    /**
     * Download PDF ticket
     */
    public function download(Booking $booking)
    {
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to ticket.');
        }

        if (!in_array($booking->status, ['paid', 'confirmed', 'completed'])) {
            abort(404, 'Ticket not found or not yet confirmed.');
        }

        // Generate PDF if not exists
        if (!$booking->ticket_pdf_path) {
            $this->generatePDF($booking);
        }

        $pdfPath = storage_path('app/public/' . $booking->ticket_pdf_path);

        if (!file_exists($pdfPath)) {
            $this->generatePDF($booking);
            $pdfPath = storage_path('app/public/' . $booking->ticket_pdf_path);
        }

        return response()->download($pdfPath, "boarding-pass-{$booking->booking_code}.pdf");
    }

    /**
     * Validate ticket via QR scan
     */
    public function validateTicket(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
            'validator_name' => 'nullable|string|max:100',
            'entry_gate' => 'nullable|string|max:50'
        ]);

        $qrData = $this->qrService->validateTicketQR($request->qr_data);

        if (!$qrData) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code'
            ], 400);
        }

        $booking = Booking::where('booking_code', $qrData['booking_code'])
                         ->where('event_id', $qrData['event_id'])
                         ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        if (!$booking->canBeValidated()) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket cannot be validated. Status: ' . $booking->status,
                'booking' => [
                    'code' => $booking->booking_code,
                    'status' => $booking->status,
                    'validated_at' => $booking->entry_validated_at
                ]
            ], 400);
        }

        $validated = $this->bookingService->validateTicket(
            $booking->booking_code,
            $request->validator_name,
            $request->entry_gate
        );

        if ($validated) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket validated successfully',
                'booking' => [
                    'code' => $booking->booking_code,
                    'event' => $booking->event->name,
                    'booker' => $booking->booker_name,
                    'quantity' => $booking->quantity,
                    'validated_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to validate ticket'
        ], 500);
    }

    /**
     * Generate PDF ticket
     */
    private function generatePDF(Booking $booking)
    {
        $qrPath = null;

        // Handle QR code path for PDF
        if ($booking->ticket_qr_code_path) {
            $fullQrPath = storage_path('app/public/' . $booking->ticket_qr_code_path);
            if (file_exists($fullQrPath)) {
                $qrPath = $fullQrPath;
            }
        }

        $data = [
            'booking' => $booking,
            'event' => $booking->event,
            'qr_path' => $qrPath
        ];

        // Set paper size and orientation for boarding pass style
        $pdf = Pdf::loadView('tickets.pdf', $data)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150,
                      'defaultFont' => 'sans-serif',
                      'isRemoteEnabled' => true,
                      'isHtml5ParserEnabled' => true
                  ]);

        $filename = "boarding-pass_{$booking->booking_code}.pdf";
        $path = "tickets/" . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        $booking->update(['ticket_pdf_path' => $path]);

        return $path;
    }

    /**
     * Show validation scanner page (for staff)
     */
    public function scanner()
    {
        return view('tickets.scanner');
    }

    /**
     * Generate QR code for ticket (if not exists)
     */
    public function generateQR(Booking $booking)
    {
        // Ensure user can only generate QR for their own tickets
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to ticket.');
        }

        if (!in_array($booking->status, ['paid', 'confirmed', 'completed'])) {
            return back()->with('error', 'Ticket QR can only be generated for confirmed bookings.');
        }

        try {
            // Generate QR code using booking service
            $this->bookingService->generateTicketQR($booking);

            return back()->with('success', 'QR Code generated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to generate QR code: ' . $e->getMessage());
        }
    }
}
