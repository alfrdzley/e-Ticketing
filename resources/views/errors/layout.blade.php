<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-case=1">
    <title>@yield('title', 'Error') - {{ config('app.name', 'Event Management') }}</title>
    
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
                <div class="mx-auto w-64 h-64 bg-gradient-to-br @yield('gradient', 'from-gray-100 to-gray-200') rounded-full flex items-center justify-center">
                    @yield('icon')
                </div>
            </div>

            {{-- Error Code --}}
            <div class="mb-6">
                <h1 class="text-8xl font-bold text-gray-300 mb-2">@yield('code', '500')</h1>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">@yield('message', 'Something went wrong')</h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    @yield('description', 'We encountered an unexpected error. Please try again later or contact support if the problem persists.')
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
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ url('/') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Home
                        </a>
                    @endauth

                    {{-- Refresh Button --}}
                    <button onclick="window.location.reload()" 
                            class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Try Again
                    </button>
                </div>

                {{-- Additional Actions --}}
                @yield('additional_actions')
            </div>

            {{-- Help Text --}}
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    @yield('help_text', 'If you continue to experience problems, please')
                    <a href="mailto:support@{{ parse_url(config('app.url'))['host'] ?? 'example.com' }}" class="text-blue-600 hover:text-blue-800">
                        contact our support team
                    </a>
                </p>
                
                @if(config('app.debug') && isset($exception))
                    <div class="mt-4 p-4 bg-gray-100 rounded-lg text-left">
                        <p class="text-xs text-gray-600 font-mono">
                            <strong>Debug Info:</strong><br>
                            {{ get_class($exception) }}: {{ $exception->getMessage() }}<br>
                            File: {{ $exception->getFile() }}:{{ $exception->getLine() }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-16 text-center">
            <p class="text-xs text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'Event Management') }}. All rights reserved.
            </p>
        </div>
    </div>

    {{-- Add some animation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const illustration = document.querySelector('.w-64.h-64');
            if (illustration) {
                illustration.style.animation = 'bounce 2s ease-in-out infinite';
            }
        });
    </script>

    <style>
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                animation-timing-function: cubic-bezier(0.755, 0.050, 0.855, 0.060);
                transform: translate3d(0, -8px, 0);
            }
            70% {
                animation-timing-function: cubic-bezier(0.755, 0.050, 0.855, 0.060);
                transform: translate3d(0, -4px, 0);
            }
            90% {
                transform: translate3d(0,-1px,0);
            }
        }
    </style>

    @yield('scripts')
</body>
</html>
