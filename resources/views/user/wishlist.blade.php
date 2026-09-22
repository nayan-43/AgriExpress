@extends('user.layouts.app')

@section('title', 'Wishlist — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Wishlist</h1>
                <p class="text-sm text-gray-500">{{ count($wishlistItems) }} saved products</p>
            </div>
            <a href="{{ route('shop') }}" class="text-brand-700 text-sm font-medium">Continue shopping <i
                    class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>

        @if (empty($wishlistItems))
            <div class="border border-dashed border-gray-200 rounded-lg py-16 text-center">
                <i class="fa-regular fa-heart text-3xl text-gray-300 mb-3"></i>
                <h2 class="font-semibold text-gray-900 mb-1">Your wishlist is empty</h2>
                <p class="text-sm text-gray-500 mb-5">Save products here while you decide.</p>
                <a href="{{ route('shop') }}"
                    class="inline-block bg-brand-700 text-white px-5 py-3 rounded-md text-sm font-medium">Browse
                    products</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @foreach ($wishlistItems as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>
@endsection
