@extends('layouts.app')

@section('title', 'Page Not Found — AgriExpress')

@php $hideNewsletter = true; @endphp

@section('content')
    <div class="flex flex-col items-center justify-center text-center px-4 py-20">
        <div class="w-40 h-40 rounded-full bg-brand-50 flex items-center justify-center mb-6">
            <i class="fa-solid fa-bag-shopping text-6xl text-brand-700"></i>
        </div>
        <h1 class="text-6xl font-bold text-brand-700 mb-3">404</h1>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Page Not Found</h2>
        <p class="text-gray-500 max-w-md mb-8">The page you are looking for might have been removed, had its name changed, or
            is temporarily unavailable.</p>
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white px-6 py-3 rounded-md font-medium">
            <i class="fa-solid fa-house"></i> Go Back Home
        </a>
    </div>
@endsection
