@extends('layouts.app')

@section('title', 'Welcome Back — AgriExpress')

@php
    $hideNewsletter = true;
    $hideFullFooter = true;
@endphp

@section('content')
    <div class="bg-brand-50 min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-md p-8">

            <div data-auth-panel="login">
                <h1 class="text-2xl font-bold text-gray-900 text-center mb-1">Welcome Back</h1>
                <p class="text-gray-500 text-sm text-center mb-6">Sign in to your account and continue shopping</p>
            </div>
            <div data-auth-panel="register" class="hidden">
                <h1 class="text-2xl font-bold text-gray-900 text-center mb-1">Create an Account</h1>
                <p class="text-gray-500 text-sm text-center mb-6">Join AgriExpress and start shopping today</p>
            </div>

            <div class="grid grid-cols-2 bg-gray-100 rounded-md p-1 mb-6 text-sm font-medium">
                <button type="button" data-auth-tab="login"
                    class="py-2 rounded-md bg-white text-brand-700 shadow-sm">Login</button>
                <button type="button" data-auth-tab="register" class="py-2 rounded-md text-gray-500">Register</button>
            </div>

            {{-- Login form --}}
            <form data-auth-panel="login" action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required
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
                        <button type="button" data-toggle-password="#login-password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa-regular fa-eye icon-eye"></i>
                            <i class="fa-regular fa-eye-slash icon-eye-slash hidden"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600"><input type="checkbox" name="remember"
                            class="accent-brand-700"> Remember me</label>
                    <a href="{{ route('password.request') }}" class="text-brand-700 hover:underline">Forgot password?</a>
                </div>
                <button type="submit"
                    class="block w-full text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium">Sign
                    In</button>
            </form>

            {{-- Register form --}}
            <form data-auth-panel="register" action="{{ route('register.attempt') }}" method="POST"
                class="space-y-4 hidden">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" placeholder="Your full name" required
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input id="register-password" type="password" name="password" placeholder="Create a password"
                            required
                            class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                        <button type="button" data-toggle-password="#register-password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa-regular fa-eye icon-eye"></i>
                            <i class="fa-regular fa-eye-slash icon-eye-slash hidden"></i>
                        </button>
                    </div>
                </div>
                <button type="submit"
                    class="block w-full text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium">Create
                    Account</button>
            </form>

            <p data-auth-panel="login" class="text-center text-sm text-gray-500 mt-6">Don't have an account? <button
                    type="button" data-auth-tab="register" class="text-brand-700 font-medium hover:underline">Create
                    one</button></p>
            <p data-auth-panel="register" class="text-center text-sm text-gray-500 mt-6 hidden">Already have an account?
                <button type="button" data-auth-tab="login" class="text-brand-700 font-medium hover:underline">Sign
                    in</button></p>
        </div>
    </div>
@endsection
