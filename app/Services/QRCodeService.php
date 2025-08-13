<?php

namespace App\Services;

use Exception;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR Code for payment (Indonesian banking standard)
     */
    public function generatePaymentQR(string $accountNumber, string $bankName, float $amount, string $description = ''): string
    {
        // Format QR content untuk QRIS standard Indonesia
        $qrData = $this->formatQRISData($bankName, $accountNumber, $amount, $description);
        
        return $this->generateQRCode($qrData, "payment_qr_" . time() . ".png");
    }

    /**
     * Format data untuk QRIS (Quick Response Code Indonesian Standard)
     */
    private function formatQRISData(string $bankName, string $accountNumber, float $amount, string $description): string
    {
        // Format yang sangat sederhana untuk QR code
        return "PAY:{$bankName}:{$accountNumber}:{$amount}:" . substr($description, 0, 20);
    }

    /**
     * Generate QR Code for ticket validation
     */
    public function generateTicketQR(string $bookingCode, int $eventId): string
    {
        $qrData = json_encode([
            'booking_code' => $bookingCode,
            'event_id' => $eventId,
            'type' => 'ticket_validation',
            'timestamp' => now()->timestamp
        ]);
        
        return $this->generateQRCode($qrData, "ticket_qr_{$bookingCode}.png");
    }

    /**
     * Validate ticket QR code
     */
    public function validateTicketQR(string $qrData): array|bool
    {
        try {
            $data = json_decode($qrData, true);
            
            if (!$data || !isset($data['booking_code'], $data['event_id'], $data['type'])) {
                return false;
            }
            
            if ($data['type'] !== 'ticket_validation') {
                return false;
            }
            
            return $data;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Generate base QR code using chillerlan/php-qrcode
     */
    private function generateQRCode(string $data, string $filename): string
    {
        try {
            // Configure QR code options with higher error correction
            $options = new QROptions([
                'version'    => 7,  // Higher version for more data capacity
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'   => QRCode::ECC_M,  // Medium error correction
                'scale'      => 6,
                'imageBase64' => false,
            ]);

            // Generate QR code
            $qrcode = new QRCode($options);
            $qrImage = $qrcode->render($data);
            
            // Save to storage
            $path = "qrcodes/" . $filename;
            Storage::disk('public')->put($path, $qrImage);

            return $path;
        } catch (Exception $e) {
            // Fallback: create a simple HTML representation
            $htmlContent = "
            <div style='text-align: center; padding: 20px; border: 2px solid #000; width: 300px; height: 300px; display: flex; align-items: center; justify-content: center; font-family: monospace; background: white;'>
                <div>
                    <h3>QR Code</h3>
                    <p style='font-size: 12px; word-break: break-all;'>{$data}</p>
                    <p><em>Scan dengan aplikasi QR Scanner</em></p>
                    <p style='color: red; font-size: 10px;'>Error: {$e->getMessage()}</p>
                </div>
            </div>";
            
            $path = "qrcodes/" . str_replace('.png', '.html', $filename);
            Storage::disk('public')->put($path, $htmlContent);
            
            return $path;
        }
    }

    /**
     * Generate static QR for event payment
     */
    public function generateEventPaymentQR($event, $bookingCode = null, $amount = null): string
    {
        if (!$event->payment_account_number) {
            throw new Exception('Event payment account not configured');
        }

        // Use specific amount if provided, otherwise use event price
        $paymentAmount = $amount ?? $event->price;
        
        $qrData = $this->formatQRISData(
            $event->payment_bank_name ?? 'BNI',
            $event->payment_account_number,
            $paymentAmount,
            $bookingCode ? "Booking: {$bookingCode}" : "Event: {$event->name}"
        );
        
        $filename = $bookingCode ? "payment_{$bookingCode}.png" : "event_payment_{$event->id}.png";
        
        return $this->generateQRCode($qrData, $filename);
    }

    /**
     * Generate QR code for specific booking payment
     */
    public function generateBookingPaymentQR($booking): string
    {
        $event = $booking->event;
        
        if (!$event->payment_account_number) {
            throw new Exception('Payment account not configured for this event');
        }

        $qrData = $this->formatQRISData(
            $event->payment_bank_name ?? 'BNI',
            $event->payment_account_number,
            $booking->total_amount,
            "Booking: {$booking->booking_code} - {$event->name}"
        );
        
        return $this->generateQRCode($qrData, "booking_payment_{$booking->booking_code}.png");
    }
}
