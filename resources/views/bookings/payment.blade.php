@extends('layouts.app')

@section('title', 'Payment Instructions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-4">
                Payment Instructions
            </h1>
            <p class="text-gray-600 text-lg">
                Complete your payment for booking <span class="font-mono font-bold text-purple-600">#{{ $booking->booking_code }}</span>
            </p>
        </div>

        {{-- Payment Countdown --}}
        @if($booking->expired_at)
        <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-2xl p-6 mb-8 text-center">
            <div class="flex items-center justify-center mb-4">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold">Payment Deadline</h3>
            </div>
            <p class="text-2xl font-bold mb-2" id="countdown">{{ $booking->expired_at->format('d M Y, H:i') }}</p>
            <p class="text-sm opacity-90">Complete your payment before this time to secure your booking</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Payment Information --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Payment Details
                </h2>

                {{-- Total Amount --}}
                <div class="bg-gradient-to-r from-purple-100 to-blue-100 rounded-xl p-6 mb-6">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-2">Total Amount to Pay</p>
                        <p class="text-4xl font-bold text-purple-600">
                            Rp {{ number_format($booking->final_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Event Info --}}
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Event Information</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Event:</span>
                            <span class="font-semibold">{{ $booking->event->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-semibold">{{ $booking->event->start_date->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Quantity:</span>
                            <span class="font-semibold">{{ $booking->quantity }} ticket(s)</span>
                        </div>
                    </div>
                </div>

                {{-- Booking Summary --}}
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Payment Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Unit Price:</span>
                            <span>Rp {{ number_format($booking->unit_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Quantity:</span>
                            <span>{{ $booking->quantity }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($booking->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount:</span>
                            <span>-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2">
                            <span>Total:</span>
                            <span class="text-purple-600">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Payment Methods
                </h2>

                {{-- Bank Transfer --}}
                @if($event->payment_account_number)
                <div class="border border-gray-200 rounded-xl p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Bank Transfer
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">Bank:</span>
                            <span class="font-semibold">{{ $event->payment_bank_name ?? 'BNI' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">Account Number:</span>
                            <div class="flex items-center">
                                <span class="font-mono font-bold mr-2">{{ $event->payment_account_number }}</span>
                                <button onclick="copyToClipboard('{{ $event->payment_account_number }}')" 
                                        class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">Account Name:</span>
                            <span class="font-semibold">{{ $event->payment_account_name ?? 'Event Organizer' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600">Amount:</span>
                            <div class="flex items-center">
                                <span class="font-mono font-bold text-purple-600 mr-2">Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</span>
                                <button onclick="copyToClipboard('{{ $booking->final_amount }}')" 
                                        class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    @if($paymentQRPath)
                    <div class="mt-6 text-center">
                        <h4 class="font-semibold text-gray-900 mb-3">QR Code untuk Transfer</h4>
                        <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-xl shadow-sm">
                            @if(str_ends_with($paymentQRPath, '.png'))
                                <img src="{{ asset('storage/' . $paymentQRPath) }}" 
                                     alt="Payment QR Code" 
                                     class="w-48 h-48 object-contain"
                                     style="image-rendering: pixelated;">
                            @elseif(str_ends_with($paymentQRPath, '.html'))
                                <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <p class="text-gray-700 font-bold mb-2">QR Code Data</p>
                                        <p class="text-xs text-gray-600 mb-2">{{ $event->payment_bank_name ?? 'Bank Transfer' }}</p>
                                        <p class="text-xs text-gray-600 mb-2">{{ $booking->booking_code }}</p>
                                        <a href="{{ asset('storage/' . $paymentQRPath) }}" target="_blank" 
                                           class="text-blue-600 text-xs underline">View Details</a>
                                    </div>
                                </div>
                            @else
                                <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <p class="text-gray-500 text-sm">QR Code tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            <strong>Scan QR code</strong> dengan aplikasi mobile banking untuk transfer otomatis<br>
                            <span class="font-mono text-purple-600 bg-purple-50 px-2 py-1 rounded">{{ $booking->booking_code }}</span>
                        </p>
                        <div class="mt-3 text-xs text-gray-400">
                            <p>💡 <strong>Tip:</strong> Gunakan kamera atau aplikasi mobile banking untuk scan QR code</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Instructions --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Payment Instructions
                    </h4>
                    <ol class="text-sm text-blue-800 space-y-2">
                        <li>1. Transfer exact amount: <strong>Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</strong></li>
                        <li>2. Use reference: <strong>{{ $booking->booking_code }}</strong></li>
                        <li>3. Upload payment proof below</li>
                        <li>4. Wait for confirmation (usually within 1-2 hours)</li>
                    </ol>
                    
                    @if($event->payment_instructions)
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>Additional Notes:</strong><br>
                            {{ $event->payment_instructions }}
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Upload Payment Proof --}}
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Upload Payment Proof</h4>
                    <form action="{{ route('bookings.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Proof Image *</label>
                            <input type="file" name="payment_proof" accept="image/*" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Upload screenshot or photo of transfer receipt</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Reference (Optional)</label>
                            <input type="text" name="payment_reference" placeholder="e.g., Transfer reference number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3" placeholder="Any additional notes..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Upload Payment Proof
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Back to Booking --}}
        <div class="text-center mt-8">
            <a href="{{ route('bookings.show', $booking) }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 font-medium transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Booking Details
            </a>
        </div>
    </div>
</div>

<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = 'Copied to clipboard!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 2000);
    });
}

// Countdown timer
@if($booking->expired_at)
function updateCountdown() {
    const expiredAt = new Date('{{ $booking->expired_at->toISOString() }}');
    const now = new Date();
    const diff = expiredAt - now;

    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'EXPIRED';
        return;
    }

    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    document.getElementById('countdown').textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();
@endif
</script>
@endsection
