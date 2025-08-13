<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Upcoming Events</h1>

    {{-- A responsive grid for the cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        @foreach ($events as $event)
            {{--
                Using the Luvi Card component with correct structure.
                - We add `flex` and `flex-col` to allow the footer to stick to the bottom.
            --}}
            <x-card class="flex flex-col overflow-hidden">

                {{-- Image Section --}}
                <img class="w-full h-48 object-cover" src="{{ $event['image'] }}" alt="Cover image for {{ $event['title'] }}">

                {{-- Main content --}}
                <div class="p-6 flex-grow">
                    <p class="text-sm text-gray-500 mb-1">{{ $event['date'] }} &middot; {{ $event['location'] }}</p>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event['title'] }}</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $event['description'] }}
                    </p>
                </div>

                {{-- Footer Section --}}
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <a href="#" class="inline-block bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        View Details
                    </a>
                </div>

            </x-card>
        @endforeach

    </div>
</div>

</body>
</html>
