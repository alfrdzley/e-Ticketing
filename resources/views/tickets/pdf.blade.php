<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $event->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #f5f7fa;
            color: #333;
            padding: 20px;
            min-height: 100vh;
        }
        
        .boarding-pass {
            max-width: 850px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            position: relative;
            display: flex;
            min-height: 450px;
        }
        
        /* Main Ticket Section */
        .ticket-main {
            flex: 2.5;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        /* Airline/Event Branding Header */
        .airline-header {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .airline-logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .airline-logo::before {
            content: "✈";
            margin-right: 10px;
            font-size: 28px;
        }
        
        .ticket-type {
            background: rgba(255,255,255,0.2);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Flight/Event Information */
        .flight-info {
            padding: 30px;
            text-align: center;
        }
        
        .route {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            position: relative;
        }
        
        .airport {
            flex: 1;
            text-align: center;
        }
        
        .airport-code {
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 5px;
        }
        
        .airport-name {
            font-size: 14px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .flight-path {
            flex: 1;
            position: relative;
            height: 2px;
            background: rgba(255,255,255,0.3);
            margin: 0 20px;
        }
        
        .flight-path::before {
            content: "✈";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.2);
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 16px;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            text-transform: uppercase;
            letter-spacing: -1px;
        }
        
        /* Flight Details Grid */
        .flight-details {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            padding: 0 30px;
        }
        
        .detail-box {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .detail-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.7;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .detail-value {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .detail-sub {
            font-size: 12px;
            opacity: 0.8;
        }
        
        /* Boarding Pass Stub */
        .boarding-stub {
            flex: 1;
            background: #f8f9fa;
            border-left: 3px dashed #ddd;
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 25px 20px;
        }
        
        /* Perforated Edge Effect */
        .boarding-stub::before {
            content: '';
            position: absolute;
            left: -2px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: repeating-linear-gradient(
                to bottom,
                #ddd 0px,
                #ddd 6px,
                transparent 6px,
                transparent 12px
            );
        }
        
        .stub-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .gate-info {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .gate-number {
            font-size: 24px;
            font-weight: 900;
            color: #333;
            margin-bottom: 5px;
        }
        
        .seat-info {
            font-size: 11px;
            color: #888;
        }
        
        /* Boarding Pass Details */
        .boarding-details {
            flex: 1;
            font-size: 11px;
            color: #666;
        }
        
        .boarding-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .boarding-item:last-child {
            border-bottom: none;
        }
        
        .boarding-label {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        
        .boarding-value {
            font-weight: 700;
            color: #333;
        }
        
        /* QR Code Section */
        .qr-section {
            text-align: center;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin: 15px 0;
        }
        
        .qr-code img {
            width: 90px;
            height: 90px;
            display: block;
            margin: 0 auto;
        }
        
        .qr-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }
        
        /* Booking Reference */
        .booking-ref {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .booking-code {
            font-size: 18px;
            font-weight: 900;
            color: #667eea;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            margin-bottom: 5px;
        }
        
        .booking-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Stub Footer */
        .stub-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 2px solid #eee;
            text-align: center;
        }
        
        .ticket-number {
            font-size: 10px;
            color: #888;
            font-family: 'Courier New', monospace;
            margin-bottom: 5px;
        }
        
        .issued-date {
            font-size: 8px;
            color: #aaa;
        }
        
        /* Price Badge */
        .price-badge {
            position: absolute;
            top: 20px;
            right: 30px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            z-index: 3;
        }
        
        .price-badge.free {
            background: rgba(40, 167, 69, 0.9);
        }
        
        /* Background Pattern */
        .ticket-main::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 40px 40px, 60px 60px;
            opacity: 0.3;
        }
        
        /* Terms and Conditions */
        .terms-section {
            max-width: 850px;
            margin: 20px auto 0;
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .terms-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }
        
        .terms-section h5 {
            color: #667eea;
            font-size: 11px;
            margin-bottom: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .terms-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .terms-section li {
            margin-bottom: 4px;
            padding-left: 12px;
            position: relative;
        }
        
        .terms-section li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
        }
        
        @media print {
            body {
                background: white;
                padding: 10px;
            }
            
            .boarding-pass, .terms-section {
                box-shadow: none;
                margin: 0;
            }
            
            .terms-section {
                margin-top: 15px;
            }
            
            @page {
                margin: 1cm;
                size: A4;
            }
        }
    </style>
</head>
<body>

    <div class="boarding-pass">
        {{-- Main Ticket Section --}}
        <div class="ticket-main">
            {{-- Price Badge --}}
            @if($event->price == 0)
                <div class="price-badge free">FREE</div>
            @else
                <div class="price-badge">Rp {{ number_format((float)$event->price, 0, ',', '.') }}</div>
            @endif
            
            {{-- Airline/Event Header --}}
            <div class="airline-header">
                <div class="airline-logo">{{ Str::limit($event->name, 15) }}</div>
                <div class="ticket-type">E-Ticket</div>
            </div>

            {{-- Flight Info Section --}}
            <div class="flight-info">
                {{-- Route Display --}}
                <div class="route">
                    <div class="airport">
                        <div class="airport-code">{{ strtoupper(Str::substr(str_replace(' ', '', $event->location), 0, 3)) }}</div>
                        <div class="airport-name">Origin</div>
                    </div>
                    <div class="flight-path"></div>
                    <div class="airport">
                        <div class="airport-code">{{ strtoupper(Str::substr(str_replace(' ', '', $event->name), 0, 3)) }}</div>
                        <div class="airport-name">Event</div>
                    </div>
                </div>

                {{-- Event Title --}}
                <div class="event-title">{{ $event->name }}</div>
            </div>

            {{-- Flight Details Grid --}}
            <div class="flight-details">
                <div class="detail-box">
                    <div class="detail-label">Departure Date</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($event->start_date)->format('d M') }}</div>
                    <div class="detail-sub">{{ \Carbon\Carbon::parse($event->start_date)->format('Y') }}</div>
                </div>
                
                <div class="detail-box">
                    <div class="detail-label">Departure Time</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</div>
                    <div class="detail-sub">Local Time</div>
                </div>
                
                <div class="detail-box">
                    <div class="detail-label">Terminal</div>
                    <div class="detail-value">{{ $event->location }}</div>
                    <div class="detail-sub">{{ Str::limit($event->address ?? 'Main Venue', 20) }}</div>
                </div>
                
                <div class="detail-box">
                    <div class="detail-label">Passenger</div>
                    <div class="detail-value">{{ Str::limit($booking->booker_name, 15) }}</div>
                    <div class="detail-sub">{{ $booking->quantity }} Pax</div>
                </div>
                
                <div class="detail-box">
                    <div class="detail-label">Class</div>
                    <div class="detail-value">General</div>
                    <div class="detail-sub">Admission</div>
                </div>
                
                <div class="detail-box">
                    <div class="detail-label">Status</div>
                    <div class="detail-value" style="color: #28a745;">CONFIRMED</div>
                    <div class="detail-sub">Paid</div>
                </div>
            </div>
        </div>

        {{-- Boarding Pass Stub --}}
        <div class="boarding-stub">
            {{-- Gate Information --}}
            <div class="stub-header">
                <div class="gate-info">Gate</div>
                <div class="gate-number">A1</div>
                <div class="seat-info">General Admission</div>
            </div>

            {{-- Booking Reference --}}
            <div class="booking-ref">
                <div class="booking-code">{{ $booking->booking_code }}</div>
                <div class="booking-label">Confirmation Code</div>
            </div>

            {{-- QR Code --}}
            @if($qr_path)
                <div class="qr-section">
                    <img src="{{ $qr_path }}" alt="Boarding Pass QR">
                    <div class="qr-label">Scan to Board</div>
                </div>
            @endif

            {{-- Boarding Details --}}
            <div class="boarding-details">
                <div class="boarding-item">
                    <span class="boarding-label">Flight</span>
                    <span class="boarding-value">{{ substr($booking->booking_code, -4) }}</span>
                </div>
                <div class="boarding-item">
                    <span class="boarding-label">Date</span>
                    <span class="boarding-value">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</span>
                </div>
                <div class="boarding-item">
                    <span class="boarding-label">Time</span>
                    <span class="boarding-value">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                </div>
                <div class="boarding-item">
                    <span class="boarding-label">Seat</span>
                    <span class="boarding-value">{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}A</span>
                </div>
                <div class="boarding-item">
                    <span class="boarding-label">Zone</span>
                    <span class="boarding-value">1</span>
                </div>
                @if($booking->payment_date)
                <div class="boarding-item">
                    <span class="boarding-label">Issued</span>
                    <span class="boarding-value">{{ \Carbon\Carbon::parse($booking->payment_date)->format('d M') }}</span>
                </div>
                @endif
            </div>

            {{-- Stub Footer --}}
            <div class="stub-footer">
                <div class="ticket-number">
                    TICKET #{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}
                </div>
                <div class="issued-date">
                    Issued: {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
    
    {{-- Terms and Conditions --}}
    <div class="terms-section">
        <div class="terms-grid">
            <div>
                <h5>🛂 Check-in Requirements</h5>
                <ul>
                    <li>Present this boarding pass at gate</li>
                    <li>Valid photo identification required</li>
                    <li>Arrive 30 minutes before departure</li>
                    <li>Security screening mandatory</li>
                    <li>Gate closes 10 minutes before event</li>
                </ul>
            </div>
            
            <div>
                <h5>📋 Terms & Conditions</h5>
                <ul>
                    <li>Non-transferable ticket</li>
                    <li>No outside food or beverages</li>
                    <li>Photography restrictions may apply</li>
                    <li>Event subject to weather conditions</li>
                    <li>Lost tickets cannot be replaced</li>
                </ul>
            </div>
            
            <div>
                <h5>ℹ️ Important Information</h5>
                <ul>
                    <li>Keep boarding pass until departure</li>
                    <li>Event times subject to change</li>
                    <li>Refund policy applies per terms</li>
                    <li>Emergency procedures posted at venue</li>
                    <li>Follow all staff instructions</li>
                </ul>
            </div>
        </div>
        
        @if($event->contact_email || $event->contact_phone)
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; text-align: center;">
            <h5 style="color: #333; font-size: 12px; margin-bottom: 10px; font-weight: bold;">✈ Customer Service</h5>
            <div style="display: flex; justify-content: center; gap: 25px; flex-wrap: wrap; font-size: 11px; color: #666;">
                @if($event->contact_email)
                <span>📧 {{ $event->contact_email }}</span>
                @endif
                @if($event->contact_phone)
                <span>📞 {{ $event->contact_phone }}</span>
                @endif
                <span>🌐 24/7 Support Available</span>
            </div>
        </div>
        @endif
    </div>
</body>
</html>