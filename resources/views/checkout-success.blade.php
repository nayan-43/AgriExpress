@extends('user.layouts.app')

@section('title', 'Payment Confirmed — AgriExpress')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-20 text-center">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
            <i class="fa-solid fa-check text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment received</h1>
        <p class="text-gray-500 mb-8">Your order is being processed. You can follow its status from your account.</p>
        <a href="{{ route('account') }}"
            class="inline-block bg-brand-700 hover:bg-brand-800 text-white px-5 py-3 rounded-md font-medium">View my
            orders</a>
    </div>
@endsection
