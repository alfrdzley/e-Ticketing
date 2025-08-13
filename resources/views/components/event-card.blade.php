{{-- 
    Enhanced Event Card Component dengan desain modern
    Menggunakan gradients, shadows, dan micro-interactions
--}}
@props([
    'event',
    'showActions' => true,
    'variant' => 'default'
])

<div class="bg-white rounded-3xl shadow-lg group hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden border border-gray-100 relative">
    {{-- Enhanced Event Image --}}
    <div class="relative overflow-hidden h-56">
        @if($event->banner_image_url)
            <img 
                src="{{ asset('storage/' . $event->banner_image_url) }}" 
                alt="{{ $event->name }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                loading="lazy"
            >
        @else
            <div class="w-full h-full bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-700 relative">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-4 left-4 w-8 h-8 bg-white rounded-full animate-ping"></div>
                    <div class="absolute bottom-4 right-4 w-6 h-6 bg-white rounded-full animate-pulse"></div>
                    <div class="absolute top-1/2 left-1/2 w-12 h-12 bg-white/30 rounded-full transform -translate-x-1/2 -translate-y-1/2 animate-bounce"></div>
                </div>
                <svg class="w-16 h-16 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
        
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
        
        {{-- Enhanced Price Badge --}}
        <div class="absolute top-4 right-4 z-10">
            @if($event->price == 0)
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold rounded-full shadow-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                    </svg>
                    GRATIS
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-bold rounded-full shadow-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Rp {{ number_format($event->price, 0, ',', '.') }}
                </span>
            @endif
        </div>
        
        {{-- Enhanced Status Badge --}}
        <div class="absolute top-4 left-4 z-10">
            <span class="inline-flex items-center px-3 py-1 bg-white/90 text-gray-800 text-xs font-semibold rounded-full backdrop-blur-sm border border-white/20">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                {{ ucfirst($event->status) }}
            </span>
        </div>

        {{-- Enhanced Favorite Button --}}
        <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
            <button class="bg-white/90 hover:bg-white text-gray-700 hover:text-red-500 p-3 rounded-full shadow-lg transition-all duration-300 hover:scale-110 backdrop-blur-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Enhanced Event Content --}}
    <div class="p-6 flex-grow relative">
        {{-- Enhanced Date & Time --}}
        <div class="flex items-center gap-6 mb-4 text-sm">
            <div class="flex items-center gap-2 text-gray-600">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="font-medium">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600">
                <div class="w-8 h-8 bg-gradient-to-r from-green-100 to-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="font-medium">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
            </div>
        </div>
        
        {{-- Enhanced Event Title --}}
        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-purple-600 transition-all duration-300">
            {{ $event->name }}
        </h3>
        
        {{-- Enhanced Event Description --}}
        <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed text-sm">
            {{ $event->description }}
        </p>
        
        {{-- Enhanced Location --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <div class="w-8 h-8 bg-gradient-to-r from-orange-100 to-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="line-clamp-1 font-medium">{{ $event->location }}</span>
        </div>
        
        {{-- Enhanced Quota Info --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">{{ $event->getAvailableQuota() }} / {{ $event->quota }} kursi tersisa</span>
                </div>
                
                {{-- Enhanced Availability Badge --}}
                @if($event->getAvailableQuota() > 0)
                    <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs rounded-full font-semibold border border-green-200">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Tersedia
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-red-100 to-pink-100 text-red-800 text-xs rounded-full font-semibold border border-red-200">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Sold Out
                    </span>
                @endif
            </div>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                @php
                    $percentage = $event->quota > 0 ? (($event->quota - $event->getAvailableQuota()) / $event->quota) * 100 : 0;
                    $colorClass = $percentage < 50 ? 'from-green-400 to-green-600' : ($percentage < 80 ? 'from-yellow-400 to-orange-500' : 'from-red-400 to-red-600');
                @endphp
                <div class="bg-gradient-to-r {{ $colorClass }} h-full rounded-full transition-all duration-1000 ease-out" 
                     style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>

    {{-- Enhanced Action Footer --}}
    @if($showActions)
    <div class="p-6 pt-0 bg-gradient-to-r from-gray-50 to-gray-100/50">
        <div class="flex gap-3">
            <a href="{{ route('events.show', $event->ulid) }}" 
               class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center py-3 px-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg transform hover:scale-[1.02] relative overflow-hidden group">
                <span class="relative z-10">Lihat Detail</span>
                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            </a>
        </div>
    </div>
    @endif
</div>

{{-- Enhanced CSS for better animations and effects --}}
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }
    
    .shimmer {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        background-size: 200px 100%;
        animation: shimmer 2s infinite;
    }
</style>
