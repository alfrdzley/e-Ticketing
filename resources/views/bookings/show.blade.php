@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-4">
                    Booking Details
                </h1>
                <p class="text-gray-600 text-lg">
                    Booking Code: <span class="font-mono font-bold text-purple-600">#{{ $booking->booking_code }}</span>
                </p>
            </div>

            {{-- Status Alert --}}
            <div class="mb-8">
                @if($booking->status === 'pending')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Pembayaran Pending:</strong> Silakan lakukan pembayaran sebelum
                                    <span class="font-mono">{{ $booking->expired_at?->format('d/m/Y H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($booking->status === 'paid')
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>Booking Dikonfirmasi!</strong> Tiket Anda sudah siap untuk digunakan.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($booking->status === 'expired')
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Booking Expired:</strong> Batas waktu pembayaran telah habis.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Booking Information --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Booking Information
                        </h2>

                        <div class="space-y-6">
                            {{-- Event Details --}}
                            <div class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Event Name</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->event->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Date & Time</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->event->start_date->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Location</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->event->location }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Address</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->event->address }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Booking Details --}}
                            <div class="border-b border-gray-200 pb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Booker Name</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->booker_name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->booker_email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->booker_phone ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Quantity</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->quantity }} ticket(s)</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Details --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Unit Price</p>
                                        <p class="font-semibold text-gray-900">
                                            Rp {{ number_format($booking->unit_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Total Amount</p>
                                        <p class="font-semibold text-gray-900">
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                    @if($booking->discount_amount > 0)
                                        <div>
                                            <p class="text-sm text-gray-500">Discount</p>
                                            <p class="font-semibold text-green-600">
                                                -Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm text-gray-500">Final Amount</p>
                                        <p class="text-xl font-bold text-purple-600">
                                            Rp {{ number_format($booking->final_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Panel --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Actions</h3>

                        @if($booking->status === 'pending')
                            @if($booking->final_amount > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <button id="pay-button-quick"
                                            class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Bayar Sekarang
                                    </button>
                                </div>

                                <div class="text-center mb-4">
                                    <p class="text-sm text-gray-600">
                                        💳 <strong>Bayar Sekarang:</strong> Credit Card, E-Wallet, Bank Transfer instan
                                </div>
                            @endif

                            Upload Payment Proof
                            @if($booking->final_amount > 0)
                                {{--                                <form action="{{ route('bookings.upload-proof', $booking) }}" method="POST"--}}
                                {{--                                      enctype="multipart/form-data" class="space-y-4">--}}
                                {{--                                    @csrf--}}
                                {{--                                    <div>--}}
                                {{--                                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti--}}
                                {{--                                            Pembayaran</label>--}}
                                {{--                                        <input type="file" name="payment_proof" accept="image/*" required--}}
                                {{--                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">--}}
                                {{--                                    </div>--}}
                                {{--                                    <div>--}}
                                {{--                                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi--}}
                                {{--                                            Pembayaran</label>--}}
                                {{--                                        <input type="text" name="payment_reference"--}}
                                {{--                                               placeholder="e.g., Transfer ref number"--}}
                                {{--                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">--}}
                                {{--                                    </div>--}}
                                {{--                                    <button type="submit"--}}
                                {{--                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">--}}
                                {{--                                        Upload Bukti--}}
                                {{--                                    </button>--}}
                                {{--                                </form>--}}
                            @endif

                        @elseif($booking->status === 'paid')
                            Ticket Actions
                            <a href="{{ route('tickets.show', $booking) }}"
                               class="w-full bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 011-1V8a1 1 0 01-1-1H4a1 1 0 00-1 1v6a1 1 0 001 1v1a2 2 0 002 2h14a2 2 0 002-2v-1a1 1 0 001-1V7a1 1 0 00-1-1h-1a1 1 0 01-1 1v2a1 1 0 01-1 1h-1a1 1 0 01-1-1V5a2 2 0 00-2-2H5z"></path>
                                </svg>
                                Lihat Tiket
                            </a>

                            @if($booking->ticket_pdf_path)
                                <a href="{{ route('tickets.download', $booking) }}"
                                   class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF
                                </a>
                            @endif
                        @endif

                        Status Information
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-3">Status Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <span class="text-sm font-semibold capitalize
                                    @if($booking->status === 'paid') text-green-600
                                    @elseif($booking->status === 'pending') text-yellow-600
                                    @else text-red-600 @endif">
                                    {{ $booking->status }}
                                </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Booking Date:</span>
                                    <span
                                        class="text-sm font-semibold">{{ $booking->booking_date->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($booking->payment_date)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Payment Date:</span>
                                        <span
                                            class="text-sm font-semibold">{{ $booking->payment_date->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($booking->expired_at && $booking->status === 'pending')
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Expires At:</span>
                                        <span
                                            class="text-sm font-semibold text-red-600">{{ $booking->expired_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="text-center mt-8">
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 font-medium transition duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Events
                </a>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap Script --}}
    @if($booking->status === 'pending' && $booking->final_amount > 0)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ config('midtrans.client_key') }}"></script>

        <script>
            // Midtrans payment function for quick payment
            document.getElementById('pay-button-quick')?.addEventListener('click', function (event) {
                event.preventDefault();

                // Show loading state
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    `;
                button.disabled = true;

                // Request payment token
                fetch('{{ route("payments.checkout", $booking) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button
                        button.innerHTML = originalText;
                        button.disabled = false;

                        if (data.success && data.snap_token) {
                            // Launch Midtrans popup
                            snap.pay(data.snap_token, {
                                onSuccess: function (result) {
                                    // Payment success
                                    console.log('Payment success:', result);
                                    showNotification('Payment successful! Checking status...', 'success');

                                    // Start polling for status update instead of immediate reload
                                    pollPaymentStatus();
                                },
                                onPending: function (result) {
                                    // Payment pending
                                    console.log('Payment pending:', result);
                                    showNotification('Payment is being processed. Checking status...', 'info');

                                    // Start polling for status update
                                    pollPaymentStatus();
                                },
                                onError: function (result) {
                                    // Payment error
                                    console.log('Payment error:', result);
                                    showNotification('Payment failed. Please try again.', 'error');
                                },
                                onClose: function () {
                                    // Payment popup closed
                                    console.log('Payment popup closed');
                                    showNotification('Payment cancelled.', 'warning');
                                }
                            });
                        } else {
                            console.error('Payment token error:', data);
                            showNotification(data.message || 'Failed to process payment. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        // Reset button
                        button.innerHTML = originalText;
                        button.disabled = false;

                        console.error('Error:', error);
                        showNotification('Connection error. Please try again.', 'error');
                    });
            });

            // Poll payment status until it's updated
            function pollPaymentStatus(maxAttempts = 10, interval = 3000) {
                let attempts = 0;

                const pollInterval = setInterval(() => {
                    attempts++;

                    fetch('{{ route("bookings.status-check", $booking) }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Status check attempt', attempts, ':', data);

                            if (data.is_paid) {
                                clearInterval(pollInterval);
                                showNotification('Payment confirmed! Redirecting...', 'success');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else if (attempts >= maxAttempts) {
                                clearInterval(pollInterval);
                                showNotification('Status check timeout. Please refresh the page manually.', 'warning');
                            }
                        })
                        .catch(error => {
                            console.error('Status check error:', error);
                            if (attempts >= maxAttempts) {
                                clearInterval(pollInterval);
                                showNotification('Failed to check status. Please refresh the page.', 'error');
                            }
                        });
                }, interval);
            }

            // Show notification function
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                const bgColor = {
                    'success': 'bg-green-500',
                    'error': 'bg-red-500',
                    'warning': 'bg-yellow-500',
                    'info': 'bg-blue-500'
                }[type] || 'bg-blue-500';

                notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-sm`;
                notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 5000);
            }
        </script>
    @endif
@endsection
