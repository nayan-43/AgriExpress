@extends('user.layouts.app')

@section('title', 'Create an Account — AgriExpress')

@php
    $hideNewsletter = true;
    $hideFullFooter = true;
@endphp

@section('content')
    <div class="bg-brand-50 min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 w-full max-w-md p-8">
            <h1 class="text-2xl font-bold text-gray-900 text-center mb-1">Create an Account</h1>
            <p class="text-gray-500 text-sm text-center mb-6">Join AgriExpress and start shopping today</p>

            @if ($errors->any())
                <div class="mb-5 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700" role="alert">
                    {{-- <p class="font-medium mb-1">Please fix the following:</p> --}}
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input id="register-name" type="text" name="name" value="{{ old('name') }}"
                        placeholder="Your full name" required autofocus
                        class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="register-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input id="register-phone" type="tel" name="phone" value="{{ old('phone') }}"
                        placeholder="Your phone number" required
                        class="w-full border {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }} rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="register-email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="you@example.com" required
                        class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input id="register-password" type="password" name="password" placeholder="Create a password"
                            required
                            class="w-full border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }} rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                        <button type="button" data-toggle-password="#register-password" aria-label="Show password"
                            aria-pressed="false" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa-regular fa-eye icon-eye"></i>
                            <i class="fa-regular fa-eye-slash icon-eye-slash" style="display: none"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="register-password-confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                        Password</label>
                    <input id="register-password-confirmation" type="password" name="password_confirmation"
                        placeholder="Repeat your password" required
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                </div>
                <button type="submit"
                    class="block w-full text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium">Create
                    Account</button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">Already have an account?
                <a href="{{ route('login') }}" class="text-brand-700 font-medium hover:underline">Sign in</a>
            </p>
        </div>
    </div>
@endsection
