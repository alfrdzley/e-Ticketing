@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('message', 'Service Unavailable')
@section('gradient', 'from-yellow-100 to-orange-200')

@section('icon')
<svg class="w-32 h-32 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
</svg>
@endsection

@section('description')
We're currently performing scheduled maintenance to improve your experience. We'll be back online shortly.
@endsection

@section('help_text')
Maintenance usually takes just a few minutes. Please check back soon, or
@endsection

@section('additional_actions')
<div class="pt-4">
    <p class="text-sm text-gray-600 mb-4">
        Follow us for updates:
    </p>
    <div class="flex justify-center space-x-4">
        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Twitter</a>
        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Facebook</a>
        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Instagram</a>
    </div>
</div>
@endsection
