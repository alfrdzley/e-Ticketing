@extends('layouts.app')

@section('title', 'QR Scanner - Ticket Validation')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-2xl mx-auto">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black text-gray-900 mb-4">🎫 QR Scanner</h1>
                <p class="text-lg text-gray-600">Scan QR code untuk validasi tiket masuk</p>
            </div>

            {{-- Scanner Card --}}
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                {{-- Camera Section --}}
                <div class="mb-6">
                    <div id="camera-container" class="relative bg-gray-900 rounded-2xl overflow-hidden aspect-square">
                        <video id="camera" class="w-full h-full object-cover" autoplay playsinline></video>
                        
                        {{-- Scanner overlay --}}
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-64 h-64 border-4 border-white border-dashed rounded-2xl relative">
                                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-500 rounded-tl-2xl"></div>
                                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-500 rounded-tr-2xl"></div>
                                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-500 rounded-bl-2xl"></div>
                                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-500 rounded-br-2xl"></div>
                                
                                {{-- Scanning line animation --}}
                                <div class="absolute inset-x-4 top-4 h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent animate-pulse"></div>
                            </div>
                        </div>

                        {{-- Status overlay --}}
                        <div id="status-overlay" class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center text-white text-xl font-bold hidden">
                            <div class="text-center">
                                <div class="animate-spin w-8 h-8 border-4 border-white border-t-transparent rounded-full mx-auto mb-4"></div>
                                <div id="status-text">Processing...</div>
                            </div>
                        </div>
                    </div>

                    {{-- Camera controls --}}
                    <div class="flex justify-center gap-4 mt-4">
                        <button id="start-camera" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                            📹 Start Camera
                        </button>
                        <button id="stop-camera" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                            ⏹️ Stop Camera
                        </button>
                    </div>
                </div>

                {{-- Manual Input Alternative --}}
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🔢 Manual Input</h3>
                    <form id="manual-form" class="space-y-4">
                        @csrf
                        <div>
                            <label for="booking-code" class="block text-sm font-medium text-gray-700 mb-2">Booking Code</label>
                            <input type="text" id="booking-code" name="booking_code" 
                                   class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4"
                                   placeholder="Masukkan kode booking (contoh: BK250809ABC123)">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="validator-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Validator</label>
                                <input type="text" id="validator-name" name="validator_name" 
                                       class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4"
                                       placeholder="Nama petugas">
                            </div>
                            
                            <div>
                                <label for="entry-gate" class="block text-sm font-medium text-gray-700 mb-2">Pintu Masuk</label>
                                <select id="entry-gate" name="entry_gate" 
                                        class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4">
                                    <option value="">Pilih pintu</option>
                                    <option value="Gate A">Gate A</option>
                                    <option value="Gate B">Gate B</option>
                                    <option value="Gate C">Gate C</option>
                                    <option value="VIP Gate">VIP Gate</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors">
                            ✅ Validate Ticket
                        </button>
                    </form>
                </div>
            </div>

            {{-- Result Card --}}
            <div id="result-card" class="mt-8 bg-white rounded-3xl p-8 shadow-xl border border-gray-100 hidden">
                <div id="result-content"></div>
            </div>
        </div>
    </div>
</div>

{{-- Include QR Scanner Library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrCode = null;

// Initialize camera
document.getElementById('start-camera').addEventListener('click', startCamera);
document.getElementById('stop-camera').addEventListener('click', stopCamera);
document.getElementById('manual-form').addEventListener('submit', validateManual);

async function startCamera() {
    try {
        const cameraElement = document.getElementById('camera');
        
        html5QrCode = new Html5Qrcode("camera-container");
        
        await html5QrCode.start(
            { facingMode: "environment" }, // Use back camera
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        );
        
        document.getElementById('start-camera').disabled = true;
        document.getElementById('stop-camera').disabled = false;
    } catch (err) {
        console.error('Camera start error:', err);
        showResult('error', 'Gagal mengakses kamera: ' + err);
    }
}

function stopCamera() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            document.getElementById('start-camera').disabled = false;
            document.getElementById('stop-camera').disabled = true;
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {
    console.log('QR Code detected:', decodedText);
    
    // Show processing overlay
    document.getElementById('status-overlay').classList.remove('hidden');
    document.getElementById('status-text').textContent = 'Processing QR Code...';
    
    // Stop scanner temporarily
    stopCamera();
    
    // Validate ticket
    validateTicket(decodedText);
}

function onScanFailure(error) {
    // Handle scan failure, usually not needed
}

async function validateTicket(qrData) {
    try {
        const formData = new FormData();
        formData.append('qr_data', qrData);
        formData.append('validator_name', document.getElementById('validator-name').value || 'QR Scanner');
        formData.append('entry_gate', document.getElementById('entry-gate').value || 'Main Gate');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

        const response = await fetch('{{ route("tickets.validate") }}', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            showResult('success', 'Tiket berhasil divalidasi!', result.booking);
        } else {
            showResult('error', result.message);
        }
    } catch (error) {
        console.error('Validation error:', error);
        showResult('error', 'Gagal memvalidasi tiket: ' + error.message);
    } finally {
        // Hide processing overlay
        document.getElementById('status-overlay').classList.add('hidden');
    }
}

async function validateManual(e) {
    e.preventDefault();
    
    const bookingCode = document.getElementById('booking-code').value;
    if (!bookingCode) {
        showResult('error', 'Masukkan kode booking terlebih dahulu');
        return;
    }
    
    // For manual input, we need to construct QR data or use a different endpoint
    await validateTicket(bookingCode);
}

function showResult(type, message, booking = null) {
    const resultCard = document.getElementById('result-card');
    const resultContent = document.getElementById('result-content');
    
    let icon = type === 'success' ? '✅' : '❌';
    let colorClass = type === 'success' ? 'text-green-600' : 'text-red-600';
    let bgClass = type === 'success' ? 'bg-green-50' : 'bg-red-50';
    
    let html = `
        <div class="${bgClass} rounded-2xl p-6">
            <div class="text-center">
                <div class="text-4xl mb-4">${icon}</div>
                <h3 class="text-xl font-bold ${colorClass} mb-2">${message}</h3>
    `;
    
    if (booking) {
        html += `
                <div class="mt-4 text-left space-y-2">
                    <p><strong>Booking Code:</strong> ${booking.code}</p>
                    <p><strong>Event:</strong> ${booking.event}</p>
                    <p><strong>Pemegang Tiket:</strong> ${booking.booker}</p>
                    <p><strong>Jumlah Tiket:</strong> ${booking.quantity}</p>
                    <p><strong>Validated At:</strong> ${booking.validated_at}</p>
                </div>
        `;
    }
    
    html += `
            </div>
        </div>
    `;
    
    resultContent.innerHTML = html;
    resultCard.classList.remove('hidden');
    
    // Auto-hide after 5 seconds and restart camera
    setTimeout(() => {
        resultCard.classList.add('hidden');
        startCamera();
    }, 5000);
}

// Auto-start camera when page loads
window.addEventListener('load', () => {
    // Add small delay to ensure DOM is ready
    setTimeout(startCamera, 1000);
});
</script>

<style>
#camera-container canvas {
    display: none !important;
}

@keyframes scan-line {
    0% { top: 1rem; }
    50% { top: calc(100% - 2rem); }
    100% { top: 1rem; }
}

.animate-scan {
    animation: scan-line 2s ease-in-out infinite;
}
</style>
@endsection
