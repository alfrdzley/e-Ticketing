@extends('layouts.app')

@section('title', 'Digital Ticket')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-full mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">
                E-Ticket
            </h1>
            <p class="text-white/80 text-lg">
                Your digital pass to an amazing experience
            </p>
        </div>

        {{-- Ticket Container with Realistic Design --}}
        <div class="relative max-w-3xl mx-auto">
            {{-- Ticket Main Body --}}
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition-all duration-300 relative">
                
                {{-- Decorative perforation line --}}
                <div class="absolute left-0 top-1/2 w-full h-0 border-t-2 border-dashed border-gray-300 transform -translate-y-1/2 z-10"></div>
                
                {{-- Left perforation holes --}}
                <div class="absolute left-0 top-1/2 w-6 h-6 bg-indigo-900 rounded-full transform -translate-x-3 -translate-y-3 z-20"></div>
                <div class="absolute right-0 top-1/2 w-6 h-6 bg-indigo-900 rounded-full transform translate-x-3 -translate-y-3 z-20"></div>
                
                {{-- Ticket Top Section --}}
                <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white overflow-hidden"
                     style="clip-path: polygon(0 0, 100% 0, 100% 85%, 95% 100%, 90% 85%, 85% 100%, 80% 85%, 75% 100%, 70% 85%, 65% 100%, 60% 85%, 55% 100%, 50% 85%, 45% 100%, 40% 85%, 35% 100%, 30% 85%, 25% 100%, 20% 85%, 15% 100%, 10% 85%, 5% 100%, 0 85%);">
                    
                    {{-- Background Pattern --}}
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-10 left-10 w-32 h-32 border border-white/20 rounded-full"></div>
                        <div class="absolute top-20 right-16 w-24 h-24 border border-white/20 rounded-full"></div>
                        <div class="absolute bottom-10 left-20 w-16 h-16 border border-white/20 rounded-full"></div>
                    </div>
                    
                    <div class="relative p-8 pb-16">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center mb-2">
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2 animate-pulse"></div>
                                    <span class="text-yellow-200 text-sm font-medium uppercase tracking-wider">Admit One</span>
                                </div>
                                <h2 class="text-3xl font-bold mb-2 leading-tight">{{ $booking->event->name }}</h2>
                                <p class="text-purple-100 text-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $booking->event->location }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 border border-white/30">
                                    <p class="text-xs text-purple-100 uppercase tracking-wide">Booking ID</p>
                                    <p class="font-mono font-bold text-xl text-yellow-300">{{ $booking->booking_code }}</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Event Date & Time Highlight --}}
                        <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-3xl font-bold text-yellow-300">{{ $booking->event->start_date->format('d') }}</div>
                                    <div class="text-sm text-purple-100 uppercase tracking-wider">{{ $booking->event->start_date->format('M Y') }}</div>
                                </div>
                                <div class="border-l border-r border-white/30">
                                    <div class="text-3xl font-bold text-yellow-300">{{ $booking->event->start_date->format('H:i') }}</div>
                                    <div class="text-sm text-purple-100 uppercase tracking-wider">Time</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold text-yellow-300">{{ $booking->quantity }}</div>
                                    <div class="text-sm text-purple-100 uppercase tracking-wider">{{ $booking->quantity == 1 ? 'Ticket' : 'Tickets' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ticket Bottom Section --}}
                {{-- Ticket Bottom Section --}}
                <div class="bg-gray-50 p-8 pt-12">
                    
                    {{-- Ticket Holder & Details --}}
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Ticket Holder</p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $booking->booker_name }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Status</p>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            CONFIRMED
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Issued</p>
                                        <p class="font-semibold text-gray-900">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- QR Code Section - Enhanced --}}
                        <div class="text-center">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-200 relative overflow-hidden">
                                {{-- QR Code Background Pattern --}}
                                <div class="absolute inset-0 opacity-5">
                                    <div class="grid grid-cols-8 gap-1 h-full">
                                        @for($i = 0; $i < 64; $i++)
                                            <div class="bg-gray-900 {{ $i % 3 == 0 ? 'opacity-100' : 'opacity-50' }}"></div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="relative z-10">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Entry Pass</h3>
                                    <p class="text-xs text-gray-500 mb-4 uppercase tracking-wider">Scan to Enter</p>
                                    
                                    @if($booking->ticket_qr_code_path)
                                        @if(Str::endsWith($booking->ticket_qr_code_path, '.png'))
                                            <div class="inline-block p-4 bg-white rounded-xl border border-gray-300 shadow-inner">
                                                <img src="{{ asset('storage/' . $booking->ticket_qr_code_path) }}" 
                                                     alt="Ticket QR Code" 
                                                     class="w-32 h-32 mx-auto"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <div class="w-32 h-32 bg-gradient-to-br from-red-100 to-red-200 rounded-xl border-2 border-red-300 flex items-center justify-center" style="display:none;">
                                                    <div class="text-center">
                                                        <svg class="w-8 h-8 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                                        </svg>
                                                        <p class="text-xs text-red-600 font-medium">QR Error</p>
                                                        <p class="font-mono text-xs mt-1 text-red-500">{{ $booking->booking_code }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="inline-block p-4 bg-white rounded-xl border border-gray-300 shadow-inner">
                                                <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border border-gray-200 flex items-center justify-center">
                                                    <div class="text-center">
                                                        <svg class="w-8 h-8 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <p class="text-xs text-gray-600 font-medium">QR Code</p>
                                                        <p class="font-mono text-xs mt-1 text-gray-500">{{ $booking->booking_code }}</p>
                                                        <a href="{{ asset('storage/' . $booking->ticket_qr_code_path) }}" 
                                                           target="_blank" 
                                                           class="text-blue-500 text-xs mt-1 block hover:text-blue-700">View Full</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="inline-block p-4 bg-white rounded-xl border border-gray-300 shadow-inner">
                                            <div class="w-32 h-32 bg-gradient-to-br from-yellow-100 to-orange-200 rounded-xl flex items-center justify-center border-2 border-yellow-300">
                                                <div class="text-center">
                                                    <svg class="w-8 h-8 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                                    </svg>
                                                    <p class="text-xs text-yellow-700 font-medium">QR Pending</p>
                                                    <form action="{{ route('tickets.generate-qr', $booking) }}" method="POST" class="mt-2">
                                                        @csrf
                                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded-lg font-medium transition-colors">
                                                            Generate
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <p class="text-xs text-gray-500 mt-3 font-medium">Present at entrance</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Entry Validation Status --}}
                    @if($booking->entry_validated_at)
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 mb-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-green-200 rounded-full transform translate-x-16 -translate-y-16 opacity-20"></div>
                            <div class="relative flex items-center">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-green-800 text-lg">✓ Entry Validated</h4>
                                    <p class="text-green-600">Validated on {{ $booking->entry_validated_at->format('d M Y, H:i') }}</p>
                                    <p class="text-sm text-green-500 mt-1">Welcome to the event! Enjoy your experience.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 mb-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-200 rounded-full transform translate-x-16 -translate-y-16 opacity-20"></div>
                            <div class="relative flex items-center">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4 animate-pulse">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-800 text-lg">Ready for Entry</h4>
                                    <p class="text-blue-600">Present this ticket at the event entrance</p>
                                    <p class="text-sm text-blue-500 mt-1">Arrive 30 minutes early for best experience</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Event Details Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Venue Information
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Location</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->event->location }}</p>
                                </div>
                                @if($booking->event->address)
                                <div>
                                    <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Address</p>
                                    <p class="text-gray-700">{{ $booking->event->address }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 01-2 2v0a2 2 0 01-2-2V11m12 10v-5m0 0V6m0 0a2 2 0 00-2-2H8a2 2 0 00-2 2v5m12 0h2m-2 0h-5"/>
                                </svg>
                                Event Schedule
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">Start Time</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->event->start_date->format('l, d F Y') }}</p>
                                    <p class="text-indigo-600 font-bold">{{ $booking->event->start_date->format('H:i') }}</p>
                                </div>
                                @if($booking->event->end_date)
                                <div>
                                    <p class="text-sm text-gray-500 uppercase tracking-wide font-medium">End Time</p>
                                    <p class="text-gray-700">{{ $booking->event->end_date->format('H:i') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Important Guidelines --}}
                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl p-6 mb-8">
                        <h4 class="font-bold text-amber-800 mb-4 flex items-center text-lg">
                            <svg class="w-6 h-6 mr-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Event Guidelines
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                        <span class="text-amber-600 text-xs font-bold">1</span>
                                    </div>
                                    <p class="text-amber-800 text-sm">Arrive 30 minutes before event start time</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                        <span class="text-amber-600 text-xs font-bold">2</span>
                                    </div>
                                    <p class="text-amber-800 text-sm">Have this digital ticket ready at entrance</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                        <span class="text-amber-600 text-xs font-bold">3</span>
                                    </div>
                                    <p class="text-amber-800 text-sm">One ticket admits one person only</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                        <span class="text-amber-600 text-xs font-bold">4</span>
                                    </div>
                                    <p class="text-amber-800 text-sm">Contact organizer for any assistance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ticket Footer with Barcode Style --}}
                <div class="bg-gray-900 text-white px-8 py-4 relative overflow-hidden">
                    {{-- Barcode Pattern Background --}}
                    <div class="absolute inset-0 opacity-10">
                        <div class="flex h-full">
                            @for($i = 0; $i < 50; $i++)
                                <div class="bg-white" style="width: {{ rand(1,4) }}px; margin-right: {{ rand(1,3) }}px;"></div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="relative flex justify-between items-center text-sm">
                        <div class="flex items-center space-x-6">
                            <div>
                                <p class="text-gray-400 text-xs">Ticket #</p>
                                <p class="font-mono font-bold">{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Series</p>
                                <p class="font-mono font-bold">{{ substr($booking->booking_code, -6) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs">Issued</p>
                            <p class="font-mono">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Action Buttons --}}
        <div class="mt-8 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('tickets.download', $booking) }}" 
                   class="group bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF Ticket
                    <div class="ml-2 opacity-70">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                
                <a href="{{ route('bookings.show', $booking) }}" 
                   class="group bg-white border-2 border-gray-300 hover:border-indigo-400 hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 font-bold py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    View Booking Details
                    <div class="ml-2 opacity-70">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <button onclick="window.print()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Print
                </button>
                
                <button onclick="shareTicket()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                    Share
                </button>
                
                <button onclick="addToCalendar()" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 01-2 2v0a2 2 0 01-2-2V11m12 10v-5m0 0V6m0 0a2 2 0 00-2-2H8a2 2 0 00-2 2v5m12 0h2m-2 0h-5"/>
                    </svg>
                    Calendar
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7m0 0V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v2M7 13h10m-5-5v10"/>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>

        {{-- Enhanced Contact Information --}}
        @if($booking->event->contact_email || $booking->event->contact_phone)
        <div class="mt-8 bg-white rounded-2xl p-8 border border-gray-200 shadow-lg relative overflow-hidden">
            {{-- Background decoration --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full transform translate-x-20 -translate-y-20 opacity-50"></div>
            
            <div class="relative">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.5a9.5 9.5 0 100 19 9.5 9.5 0 000-19z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Need Assistance?</h3>
                    <p class="text-gray-600">Our event organizers are here to help you</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($booking->event->contact_email)
                    <a href="mailto:{{ $booking->event->contact_email }}" 
                       class="group bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border border-blue-200 rounded-xl p-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 font-medium uppercase tracking-wide">Email Support</p>
                                <p class="font-semibold text-gray-900 group-hover:text-blue-700">{{ $booking->event->contact_email }}</p>
                                <p class="text-xs text-gray-500 mt-1">Click to send email</p>
                            </div>
                        </div>
                    </a>
                    @endif
                    
                    @if($booking->event->contact_phone)
                    <a href="tel:{{ $booking->event->contact_phone }}" 
                       class="group bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 border border-green-200 rounded-xl p-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-green-600 font-medium uppercase tracking-wide">Phone Support</p>
                                <p class="font-semibold text-gray-900 group-hover:text-green-700">{{ $booking->event->contact_phone }}</p>
                                <p class="text-xs text-gray-500 mt-1">Click to call now</p>
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Footer Note --}}
        <div class="text-center mt-8 text-gray-500">
            <div class="inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-sm">This is a secure digital ticket</span>
            </div>
        </div>
    </div>
</div>

<script>
function shareTicket() {
    if (navigator.share) {
        navigator.share({
            title: 'Event Ticket - {{ $booking->event->name }}',
            text: 'Check out my ticket for {{ $booking->event->name }}!',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Ticket link copied to clipboard!');
        });
    }
}

function addToCalendar() {
    const event = {
        title: '{{ addslashes($booking->event->name) }}',
        start: '{{ $booking->event->start_date->format('Ymd\THis') }}',
        end: '{{ $booking->event->end_date ? $booking->event->end_date->format('Ymd\THis') : $booking->event->start_date->addHours(2)->format('Ymd\THis') }}',
        location: '{{ addslashes($booking->event->location) }}',
        description: 'Booking Code: {{ $booking->booking_code }}'
    };
    
    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${event.start}/${event.end}&location=${encodeURIComponent(event.location)}&details=${encodeURIComponent(event.description)}`;
    
    window.open(googleCalendarUrl, '_blank');
}
</script>

{{-- Print Styles --}}
<style>
@media print {
    body { background: white !important; }
    .bg-gradient-to-br { background: white !important; }
    .shadow-2xl, .shadow-lg { box-shadow: none !important; }
    .hover\:scale-\[1\.02\]:hover { transform: none !important; }
    .transform { transform: none !important; }
    button, .no-print { display: none !important; }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
@endsection
