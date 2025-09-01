<x-app-layout>
    <x-slot name="header">
        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Error Message --}}
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Dashboard Error</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            @endif

            {{-- Check if required variables exist --}}
            @if(!isset($user) || !isset($stats) || !isset($bookings))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Missing Data</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Required dashboard data is missing. Please check the controller.</p>
                                <ul class="list-disc list-inside mt-1">
                                    @if(!isset($user))
                                    <li>User data missing</li> @endif
                                    @if(!isset($stats))
                                    <li>Stats data missing</li> @endif
                                    @if(!isset($bookings))
                                    <li>Bookings data missing</li> @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-white bg-opacity-30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-blue-100 truncate">Total Bookings</dt>
                                        <dd class="text-lg font-semibold text-white">{{ $stats['total_bookings'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-white bg-opacity-30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-green-100 truncate">Confirmed</dt>
                                        <dd class="text-lg font-semibold text-white">{{ $stats['confirmed_bookings'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-white bg-opacity-30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-yellow-100 truncate">Pending</dt>
                                        <dd class="text-lg font-semibold text-white">{{ $stats['pending_bookings'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-500 to-red-600 overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-white bg-opacity-30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-red-100 truncate">Cancelled</dt>
                                        <dd class="text-lg font-semibold text-white">{{ $stats['cancelled_bookings'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-white bg-opacity-30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-purple-100 truncate">Total Spent</dt>
                                        <dd class="text-lg font-semibold text-white">Rp
                                            {{ number_format($stats['total_spent'], 0, ',', '.') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('events.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out">
                                🎫 Browse Events
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition duration-150 ease-in-out">
                                ⚙️ Edit Profile
                            </a>
                            
                        </div>
                    </div>
                </div>

                {{-- Dashboard Charts Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Booking Status Chart --}}
                    <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Status Distribution</h3>
                            <div class="relative">
                                <canvas id="bookingStatusChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Monthly Booking Trend --}}
                    <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Booking Activity</h3>
                            <div class="relative">
                                <canvas id="monthlyTrendChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Bookings --}}
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                            @if($bookings->count() > 0)
                                <span class="text-sm text-gray-500">{{ $bookings->total() }} total bookings</span>
                            @endif
                        </div>

                        @if($bookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($bookings as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        @if($booking->event->start_date > now())
                                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h4 class="text-sm font-semibold text-gray-900">{{ $booking->event->name }}
                                                        </h4>
                                                        <p class="text-sm text-gray-600">Booking Code: {{ $booking->booking_code }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            Booked: {{ $booking->created_at->format('d M Y, H:i') }} 
                                                            @if($booking->event->start_date)
                                                                | Event: {{ $booking->event->start_date->format('d M Y, H:i') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                <div class="text-right">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $booking->quantity }}
                                                        ticket(s)</p>
                                                    <p class="text-sm text-gray-600">Rp
                                                        {{ number_format($booking->final_amount, 0, ',', '.') }}</p>
                                                </div>

                                                @if($booking->status === 'paid' || $booking->status === 'confirmed')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Confirmed
                                                    </span>
                                                @elseif($booking->status === 'completed')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        🎉 Completed
                                                    </span>
                                                @elseif($booking->status === 'pending')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Pending
                                                    </span>
                                                @elseif($booking->status === 'cancelled')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ❌ Cancelled
                                                    </span>
                                                @elseif($booking->status === 'expired')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        ⏰ Expired
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center space-x-2 ml-4">
                                                @if($booking->status === 'paid' || $booking->status === 'confirmed' || $booking->status === 'completed')
                                                    <a href="{{ route('tickets.show', $booking) }}"
                                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                        View Ticket
                                                    </a>
                                                @elseif($booking->status === 'pending')
                                                    <a href="{{ route('bookings.payment', $booking) }}"
                                                        class="text-orange-600 hover:text-orange-900 text-sm font-medium">
                                                        Pay Now
                                                    </a>
                                                @elseif($booking->status === 'cancelled')
                                                    <span class="text-gray-400 text-sm">
                                                        Cancelled
                                                    </span>
                                                @endif
                                                <a href="{{ route('bookings.show', $booking) }}"
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                                    Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $bookings->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings yet</h3>
                                <p class="text-gray-600 mb-4">Start by browsing our amazing events!</p>
                                <a href="{{ route('events.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out">
                                    Browse Events
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

    <script>
        // Only initialize charts if we have data
        @if(isset($stats) && $stats['total_bookings'] > 0)
            
        // Booking Status Doughnut Chart
        const statusCtx = document.getElementById('bookingStatusChart');
        if (statusCtx) {
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Confirmed', 'Pending', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $stats['confirmed_bookings'] }},
                            {{ $stats['pending_bookings'] }},
                            {{ $stats['cancelled_bookings'] }}
                        ],
                        backgroundColor: [
                            '#10B981', 
                            '#F59E0B', 
                            '#EF4444'  
                        ],
                        borderColor: [
                            '#059669',
                            '#D97706', 
                            '#DC2626'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

                // Monthly Booking Trend Line Chart
        const trendCtx = document.getElementById('monthlyTrendChart');
        if (trendCtx) {
            // Generate last 6 months data
            const months = [];
            const bookingsData = [];
            
            @php
                $monthlyData = [];
                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $count = $user->bookings()
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count();
                    $monthlyData[] = [
                        'month' => $month->format('M Y'),
                        'count' => $count
                    ];
                }
            @endphp
            
            @foreach($monthlyData as $data)
                months.push('{{ $data["month"] }}');
                bookingsData.push({{ $data["count"] }});
            @endforeach

            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Monthly Bookings',
                        data: bookingsData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#3B82F6',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }
        
        @else
            // If no data, show placeholder message in chart containers
            document.addEventListener('DOMContentLoaded', function() {
                const statusChart = document.getElementById('bookingStatusChart');
                const trendChart = document.getElementById('monthlyTrendChart');
                
                if (statusChart) {
                    statusChart.style.display = 'none';
                    statusChart.parentElement.innerHTML = '<div class="flex items-center justify-center h-48 text-gray-500"><p>No booking data available for chart</p></div>';
                }
                
                if (trendChart) {
                    trendChart.style.display = 'none';
                    trendChart.parentElement.innerHTML = '<div class="flex items-center justify-center h-48 text-gray-500"><p>No monthly data available for chart</p></div>';
                }
            });
        @endif
    </script>
    </x-slot>
</x-app-layout>