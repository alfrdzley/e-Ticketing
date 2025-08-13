@extends('errors.layout')

@section('title', 'Session Expired')
@section('code', '419')
@section('message', 'Session Expired')
@section('gradient', 'from-orange-100 to-red-200')

@section('icon')
<svg class="w-32 h-32 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('description')
Your session has expired for security reasons. This usually happens when you've been inactive for too long or when there's a CSRF token mismatch.
@endsection

@section('help_text')
Please refresh the page and try again. If the problem persists,
@endsection

@section('additional_actions')
<div class="pt-4">
    @auth
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            Go back to your dashboard →
        </a>
    @else
        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            Login again →
        </a>
    @endauth
</div>
@endsection
