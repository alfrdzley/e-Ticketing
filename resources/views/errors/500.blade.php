@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message', 'Internal Server Error')
@section('gradient', 'from-red-100 to-pink-200')

@section('icon')
<svg class="w-32 h-32 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
</svg>
@endsection

@section('description')
We're experiencing some technical difficulties on our end. Our team has been notified and is working to resolve this issue.
@endsection

@section('help_text')
This error has been automatically reported to our development team. Please try again in a few minutes, or
@endsection

@section('additional_actions')
<div class="pt-4">
    <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
        Browse our events while we fix this →
    </a>
</div>
@endsection
