@extends('layouts.app')

@section('title', 'Events Terbaru')

@section('content')
    <div class="min-h-screen bg-gray-50">

        {{-- Enhanced Hero Section --}}
        <div class="relative bg-gradient-to-br from-blue-600 via-purple-700 to-indigo-800 text-white overflow-hidden">
            {{-- Animated Background Elements --}}
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full mix-blend-multiply filter blur-xl animate-pulse">
                </div>
                <div
                    class="absolute top-40 right-10 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000">
                </div>
                <div
                    class="absolute -bottom-32 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000">
                </div>
            </div>

            {{-- Pattern Overlay --}}
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=" 60" height="60" viewBox="0 0 60 60"
                xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="none" fill-rule="evenodd" %3E%3Cg fill="%23ffffff"
                fill-opacity="0.05" %3E%3Ccircle cx="7" cy="7" r="1" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

            <div class="relative container mx-auto px-6 py-24">
                <div class="text-center max-w-5xl mx-auto">
                    {{-- Main Title with improved animation --}}
                    <div class="mb-8">
                        <h1 class="text-6xl md:text-8xl font-black mb-4 tracking-tight">
                            <span class="inline-block animate-fade-in-up">Discover</span>
                            <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 inline-block animate-fade-in-up animation-delay-300">Amazing
                                Events</span>
                        </h1>
                        <div
                            class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto rounded-full animate-fade-in-up animation-delay-600">
                        </div>
                    </div>

                    <p
                        class="text-xl md:text-2xl opacity-90 mb-12 leading-relaxed max-w-3xl mx-auto animate-fade-in-up animation-delay-900">
                        Bergabunglah dengan komunitas terbaik dan kembangkan potensi diri melalui berbagai event menarik
                    </p>

                    {{-- Enhanced Quick Stats --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 animate-fade-in-up animation-delay-1200">
                        <div
                            class="group bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                            <div class="text-5xl font-black mb-3 text-yellow-400">{{ count($events) }}</div>
                            <div class="text-sm opacity-75 uppercase tracking-widest font-semibold">Event Tersedia</div>
                            <div
                                class="w-full h-1 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mt-4 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                            </div>
                        </div>
                        <div
                            class="group bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                            <div class="text-5xl font-black mb-3 text-green-400">
                                {{ collect($events)->where('price', 0)->count() }}</div>
                            <div class="text-sm opacity-75 uppercase tracking-widest font-semibold">Event Gratis</div>
                            <div
                                class="w-full h-1 bg-gradient-to-r from-green-400 to-blue-500 rounded-full mt-4 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                            </div>
                        </div>
                        <div
                            class="group bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                            <div class="text-5xl font-black mb-3 text-blue-400">{{ collect($events)->sum('quota') }}</div>
                            <div class="text-sm opacity-75 uppercase tracking-widest font-semibold">Total Kursi</div>
                            <div
                                class="w-full h-1 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full mt-4 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300">
                            </div>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <div class="animate-fade-in-up animation-delay-1500">
                        <a href="#events"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-full hover:from-yellow-300 hover:to-orange-400 transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                            <span>Jelajahi Events</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m0 0l7-7m0 7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Wave Separator --}}
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 320" class="w-full h-20 text-gray-50">
                    <path fill="currentColor" fill-opacity="1"
                        d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>

        {{-- Enhanced Events Section --}}
        <section id="events" class="py-20 bg-gray-50">
            <div class="container mx-auto px-6">
                {{-- Section Header --}}
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                        Event <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Pilihan</span>
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mx-auto rounded-full mb-6"></div>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Pilih dari berbagai kategori event yang telah kami kurasi khusus untuk pengembangan diri Anda
                    </p>
                </div>

                {{-- Events Grid with improved layout --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($events as $event)
                        <div class="animate-fade-in-up" style="animation-delay: {{ $loop->index * 100 }}ms;">
                            <x-event-card :event="$event" />
                        </div>
                    @endforeach
                </div>

                {{-- Enhanced Empty State --}}
                @if(empty($events) || count($events) == 0)
                    <div class="text-center py-20">
                        <div
                            class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Event Tersedia</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Saat ini belum ada event yang tersedia. Panteus terus halaman ini untuk update event terbaru!
                        </p>
                        <button
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-full hover:shadow-lg transition-all transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh Halaman
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <section class="py-20 bg-gradient-to-r from-gray-900 to-gray-800 text-white">
            <div class="container mx-auto px-6 text-center">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Jangan Lewatkan Event Terbaru!</h3>
                <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                    Dapatkan notifikasi untuk setiap event baru yang menarik dan sesuai dengan minat Anda
                </p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Masukkan email Anda"
                        class="flex-1 px-6 py-3 rounded-full text-gray-900 focus:ring-4 focus:ring-blue-500/50 focus:outline-none">
                    <button
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                        Subscribe
                    </button>
                </div>
            </div>
        </section>
    </div>

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

        .animation-delay-300 {
            animation-delay: 300ms;
        }

        .animation-delay-600 {
            animation-delay: 600ms;
        }

        .animation-delay-900 {
            animation-delay: 900ms;
        }

        .animation-delay-1200 {
            animation-delay: 1200ms;
        }

        .animation-delay-1500 {
            animation-delay: 1500ms;
        }

        .animation-delay-2000 {
            animation-delay: 2000ms;
        }

        .animation-delay-4000 {
            animation-delay: 4000ms;
        }
    </style>
@endsection