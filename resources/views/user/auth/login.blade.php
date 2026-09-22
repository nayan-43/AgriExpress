@extends('user.layouts.app')

@section('title', 'Welcome Back — AgriExpress')

@php
    $hideNewsletter = true;
    $hideFullFooter = true;
@endphp

@section('content')
    <div class="bg-brand-50 min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-md p-8">

            @if (session('status'))
                <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700"
                    role="status">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-5 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <h1 class="text-2xl font-bold text-gray-900 text-center mb-1">Welcome Back</h1>
            <p class="text-gray-500 text-sm text-center mb-6">Sign in to your account and continue shopping</p>

            <form action="{{ route('authenticate') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input id="login-password" type="password" name="password" placeholder="Enter your password"
                            required
                            class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                        <button type="button" data-toggle-password="#login-password" aria-label="Show password"
                            aria-pressed="false" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa-regular fa-eye icon-eye"></i>
                            <i class="fa-regular fa-eye-slash icon-eye-slash" style="display: none"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600"><input type="checkbox" name="remember"
                            class="accent-brand-700"> Remember me</label>
                    <a href="{{ route('forgot-password') }}" class="text-brand-700 hover:underline">Forgot password?</a>
                </div>
                <button type="submit"
                    class="block w-full text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium">Sign
                    In</button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">Don't have an account?
                <a href="{{ route('register') }}" class="text-brand-700 font-medium hover:underline">Create one</a>
            </p>
        </div>
    </div>
@endsection
