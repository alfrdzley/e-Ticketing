<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found - {{ config('app.name', 'Event Management') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        {{-- Main Error Container --}}
        <div class="max-w-lg w-full text-center">
            {{-- Error Illustration --}}
            <div class="mb-8">
                <div class="mx-auto w-64 h-64 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center">
                    <svg class="w-32 h-32 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.5.90-6.115 2.395M2.05 11.293A9.001 9.001 0 0112 3c2.485 0 4.751 1.007 6.395 2.64"/>
                    </svg>
                </div>
            </div>

            {{-- Error Code --}}
            <div class="mb-6">
                <h1 class="text-8xl font-bold text-gray-300 mb-2">404</h1>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Page Not Found</h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Oops! The page you're looking for doesn't exist. It might have been moved, deleted, or you entered the wrong URL.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    {{-- Go Back Button --}}
                    <button onclick="window.history.back()" 
                            class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Go Back
                    </button>

                    {{-- Home Button --}}
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go Home
                    </a>
                </div>

                {{-- Browse Events Link --}}
                <div class="pt-4">
                    <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Or browse our amazing events →
                    </a>
                </div>
            </div>

            {{-- Help Text --}}
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Need help? 
                    <a href="mailto:support@{{ parse_url(config('app.url'))['host'] ?? 'example.com' }}" class="text-blue-600 hover:text-blue-800">
                        Contact our support team
                    </a>
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-16 text-center">
            <p class="text-xs text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'Event Management') }}. All rights reserved.
            </p>
        </div>
    </div>

    {{-- Add some fun animation --}}
    <script>
        // Add floating animation to the illustration
        document.addEventListener('DOMContentLoaded', function() {
            const illustration = document.querySelector('.w-64.h-64');
            if (illustration) {
                illustration.style.animation = 'float 3s ease-in-out infinite';
            }
        });
    </script>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</body>
</html>
