@extends('user.layouts.app')

@section('title', 'Payment Cancelled — AgriExpress')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-20 text-center">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
            <i class="fa-solid fa-arrow-left text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment cancelled</h1>
        <p class="text-gray-500 mb-8">Your cart is still available. You can return to checkout whenever you are ready.</p>
        <a href="{{ route('checkout') }}"
            class="inline-block bg-brand-700 hover:bg-brand-800 text-white px-5 py-3 rounded-md font-medium">Return to
            checkout</a>
    </div>
@endsection
