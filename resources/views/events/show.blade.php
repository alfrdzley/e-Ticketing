@extends('layouts.app')

@section('title', $event->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Enhanced Back Button --}}
    <div class="border-b border-gray-100 sticky top-0 z-40 backdrop-blur-sm bg-white/95">
        <div class="container mx-auto px-6 py-4">
            <a href="{{ route('events.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-all duration-300 group">
                <div class="w-8 h-8 bg-gray-100 group-hover:bg-gray-200 rounded-full flex items-center justify-center mr-3 transition-colors">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <span class="font-medium">Kembali ke Events</span>
            </a>
        </div>
    </div>

    {{-- Notifications --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-6 mt-4 rounded-r-lg shadow-sm" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-6 mt-4 rounded-r-lg shadow-sm" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-6 mt-4 rounded-r-lg shadow-sm" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Enhanced Hero Section --}}
    <div class="relative overflow-hidden">
        @if($event->banner_image_url)
            <div class="h-96 lg:h-[600px] w-full relative">
                <img src="{{ asset('storage/' . $event->banner_image_url) }}" 
                     alt="{{ $event->name }}"
                     class="w-full h-full object-cover">
                {{-- Enhanced overlay with better gradient --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                {{-- Parallax effect overlay --}}
                <div class="absolute inset-0 bg-gradient-to-r from-blue-900/30 to-purple-900/30"></div>
            </div>
        @else
            <div class="h-96 lg:h-[600px] w-full bg-gradient-to-br from-blue-600 via-purple-700 to-indigo-800 relative overflow-hidden">
                {{-- Animated background elements --}}
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-xl animate-pulse"></div>
                    <div class="absolute bottom-20 right-20 w-96 h-96 bg-yellow-300 rounded-full mix-blend-overlay filter blur-2xl animate-pulse animation-delay-2000"></div>
                    <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-pink-300 rounded-full mix-blend-overlay filter blur-xl animate-pulse animation-delay-4000 transform -translate-x-1/2 -translate-y-1/2"></div>
                </div>
                {{-- Pattern overlay --}}
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <circle cx="5" cy="5" r="1" fill="white"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid)"/>
                    </svg>
                </div>
                {{-- Center icon --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm border border-white/20">
                            <svg class="w-16 h-16 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-white/60 text-lg font-medium">Event Image</p>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Floating event title --}}
        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <div class="container mx-auto">
                <div class="max-w-4xl">
                    <h1 class="text-4xl md:text-6xl font-black mb-4 leading-tight">{{ $event->name }}</h1>
                    <div class="flex flex-wrap items-center gap-6 text-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Enhanced Event Status Badge --}}
        <div class="absolute top-8 right-8">
            @if($event->getAvailableQuota() > 0)
                <div class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="w-3 h-3 bg-white rounded-full mr-2 animate-pulse"></div>
                    <span>{{ $event->getAvailableQuota() }} Kursi Tersisa</span>
                </div>
            @else
                <div class="inline-flex items-center px-6 py-3 rounded-full text-sm font-bold bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg backdrop-blur-sm border border-white/20">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span>Sold Out</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Enhanced Main Content --}}
    <div class="container mx-auto px-6 py-12 -mt-20 relative z-10">
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Enhanced Event Details --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Enhanced Event Header Card --}}
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative overflow-hidden">
                    {{-- Decorative background --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full transform translate-x-16 -translate-y-16 opacity-50"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h2 class="text-3xl font-black text-gray-900 mb-2">Detail Event</h2>
                                <div class="w-16 h-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full"></div>
                            </div>
                            {{-- Price badge --}}
                            @if($event->price == 0)
                                <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl font-bold text-lg shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                    </svg>
                                    GRATIS
                                </div>
                            @else
                                <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl font-bold text-lg shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                        
                        {{-- Enhanced Event Meta Info Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-lg">Tanggal & Waktu</div>
                                        <div class="text-gray-600 font-medium">{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y, H:i') }}</div>
                                        @if($event->end_date)
                                            <div class="text-sm text-gray-500">s/d {{ \Carbon\Carbon::parse($event->end_date)->format('d F Y, H:i') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-lg">Lokasi</div>
                                        <div class="text-gray-600 font-medium">{{ $event->location }}</div>
                                        @if($event->address)
                                            <div class="text-sm text-gray-500">{{ $event->address }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-900 text-lg">Kapasitas</div>
                                        <div class="text-gray-600 font-medium">{{ $event->getAvailableQuota() }} / {{ $event->quota }} kursi tersisa</div>
                                        <div class="mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                @php
                                                    $percentage = $event->quota > 0 ? (($event->quota - $event->getAvailableQuota()) / $event->quota) * 100 : 0;
                                                    $colorClass = $percentage < 50 ? 'from-green-400 to-green-600' : ($percentage < 80 ? 'from-yellow-400 to-orange-500' : 'from-red-400 to-red-600');
                                                @endphp
                                                <div class="bg-gradient-to-r {{ $colorClass }} h-full rounded-full transition-all duration-1000 ease-out transform origin-left" 
                                                     style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Enhanced Event Description --}}
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative overflow-hidden">
                    {{-- Decorative background --}}
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-green-100 to-blue-100 rounded-full transform -translate-x-20 translate-y-20 opacity-50"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-black text-gray-900">Tentang Event</h2>
                        </div>
                        
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            {!! $event->description !!}
                        </div>
                        @if($event->terms_conditions)
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.194-.833-2.964 0L3.848 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Syarat & Ketentuan</h3>
                                </div>
                                <div class="text-gray-600 bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    {!! $event->terms_conditions !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Enhanced Booking Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 sticky top-24 overflow-hidden">
                    {{-- Header with gradient --}}
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white relative overflow-hidden">
                        {{-- Decorative elements --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full transform -translate-x-12 translate-y-12"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold">Booking Tiket</h3>
                            </div>
                            <p class="text-white/80 text-sm">Dapatkan tiket Anda sekarang!</p>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        {{-- Enhanced Price Display --}}
                        <div class="text-center mb-8 p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border border-gray-200 relative overflow-hidden">
                            {{-- Decorative background --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-purple-50/50"></div>
                            
                            <div class="relative z-10">
                                @if($event->price == 0)
                                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 mb-2">GRATIS</div>
                                    <div class="text-sm text-gray-600">Event tanpa biaya</div>
                                    <div class="inline-flex items-center mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                        </svg>
                                        Free Event
                                    </div>
                                @else
                                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-2">Rp {{ number_format($event->price, 0, ',', '.') }}</div>
                                    <div class="text-sm text-gray-600">per tiket</div>
                                    <div class="inline-flex items-center mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Paid Event
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($event->getAvailableQuota() > 0)
                            @auth
                                {{-- Enhanced Booking Form untuk User yang sudah login --}}
                                <form action="{{ route('bookings.store', $event->ulid) }}" method="POST" class="space-y-6" id="bookingForm">
                                    @csrf
                                    
                                    <div class="space-y-2">
                                        <label for="quantity" class="block text-sm font-bold text-gray-700">Jumlah Tiket</label>
                                        <select name="quantity" id="quantity" class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4 bg-white">
                                            @for($i = 1; $i <= min(5, $event->getAvailableQuota()); $i++)
                                                <option value="{{ $i }}">{{ $i }} tiket</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="name" class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" required 
                                               value="{{ Auth::user()?->name ?? '' }}"
                                               class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4"
                                               placeholder="Masukkan nama lengkap">
                                    </div>

                                    <div class="space-y-2">
                                        <label for="email" class="block text-sm font-bold text-gray-700">Email</label>
                                        <input type="email" name="email" id="email" required 
                                               value="{{ Auth::user()?->email ?? '' }}"
                                               class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4"
                                               placeholder="Masukkan email">
                                    </div>

                                    <div class="space-y-2">
                                        <label for="phone" class="block text-sm font-bold text-gray-700">No. Telepon</label>
                                        <input type="tel" name="phone" id="phone" required 
                                               value="{{ Auth::user()->phone }}"
                                               class="w-full rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 py-3 px-4"
                                               placeholder="Masukkan nomor telepon">
                                    </div>

                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl relative overflow-hidden group"
                                            onclick="console.log('Form submitting...'); this.innerHTML='<span>Processing...</span>'; this.disabled=true; setTimeout(() => this.form.submit(), 100);">
                                        <span class="relative z-10 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Book Sekarang
                                        </span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    </button>
                                </form>
                            @else
                                {{-- Login Required Message --}}
                                <div class="text-center py-8">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-3">Login Required</h4>
                                    <p class="text-gray-600 text-sm mb-6">Silakan login atau register terlebih dahulu untuk melakukan booking tiket.</p>
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                                            🔑 Login
                                        </a>
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
                                            📝 Register
                                        </a>
                                    </div>
                                </div>
                            @endauth

                            {{-- Quota indicator --}}
                            <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-green-800">Sisa Tiket</span>
                                    <span class="text-sm font-bold text-green-600">{{ $event->getAvailableQuota() }} / {{ $event->quota }}</span>
                                </div>
                                <div class="w-full bg-green-200 rounded-full h-2 overflow-hidden">
                                    @php
                                        $percentage = $event->quota > 0 ? (($event->quota - $event->getAvailableQuota()) / $event->quota) * 100 : 0;
                                    @endphp
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-full rounded-full transition-all duration-1000 ease-out" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @else
                            {{-- Enhanced Sold Out State --}}
                            <div class="text-center py-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-red-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-pink-600 rounded-full animate-ping opacity-20"></div>
                                    <svg class="w-10 h-10 text-red-600 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900 mb-3">Tiket Habis</h4>
                                <p class="text-gray-600 text-sm mb-4">Maaf, tiket untuk event ini sudah habis terjual.</p>
                                <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Sold Out
                                </div>
                            </div>
                        @endif

                        {{-- Enhanced Contact Info --}}
                        @if($event->contact_email || $event->contact_phone)
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Informasi Kontak
                                </h4>
                                <div class="space-y-3">
                                    @if($event->contact_email)
                                        <a href="mailto:{{ $event->contact_email }}" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-blue-50 rounded-xl transition-colors group">
                                            <div class="w-8 h-8 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <span class="text-blue-600 hover:text-blue-700 font-medium">{{ $event->contact_email }}</span>
                                        </a>
                                    @endif
                                    @if($event->contact_phone)
                                        <a href="tel:{{ $event->contact_phone }}" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-green-50 rounded-xl transition-colors group">
                                            <div class="w-8 h-8 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </div>
                                            <span class="text-green-600 hover:text-green-700 font-medium">{{ $event->contact_phone }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Enhanced CSS for animations --}}
<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animation-delay-2000 { animation-delay: 2000ms; }
    .animation-delay-4000 { animation-delay: 4000ms; }
    
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .gradient-animate {
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
    }
</style>

<script>
// Form debugging
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form is being submitted!');
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
            
            // Check if all required fields are filled
            const requiredFields = form.querySelectorAll('[required]');
            let allFilled = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    console.log('Empty required field:', field.name);
                    allFilled = false;
                }
            });
            
            if (!allFilled) {
                console.log('Some required fields are empty');
                return false;
            }
            
            console.log('All validation passed, submitting...');
            return true;
        });
    }
});
</script>
@endsection
