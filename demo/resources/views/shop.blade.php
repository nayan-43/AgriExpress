@extends('layouts.app')

@section('title', 'Shop — AgriExpress')

@section('content')

    {{-- Small page banner (not full-bleed hero — a lightweight section banner) --}}
    <section class="bg-brand-50">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs text-gray-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
                <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i> Shop
            </p>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Shop Our Collection</h1>
            <p class="text-gray-500 text-sm">Discover top quality products at the best prices.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[260px_1fr] gap-8">

        {{-- Sidebar filters --}}
        <aside class="space-y-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Categories</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>
                        <a href="{{ route('shop') }}"
                            class="flex justify-between {{ !request('category') ? 'text-brand-700 font-medium' : 'hover:text-brand-700' }}">
                            <span>All Categories</span><span>{{ $totalCount }}</span>
                        </a>
                    </li>
                    @foreach ($filterCategories as $cat)
                        <li>
                            <a href="{{ route('shop') }}?category={{ $cat['slug'] }}"
                                class="flex justify-between {{ request('category') === $cat['slug'] ? 'text-brand-700 font-medium' : 'hover:text-brand-700' }}">
                                <span>{{ $cat['name'] }}</span><span>{{ $cat['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Price Range</h3>
                <input type="range" min="0" max="500" class="w-full accent-brand-700">
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>$0</span><span>$500</span>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Brand</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    @foreach ($brands as $brand)
                        <label class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><input type="checkbox" class="accent-brand-700">
                                {{ $brand['name'] }}</span>
                            <span class="text-gray-400">{{ $brand['count'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Product grid --}}
        <div>
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-gray-500">{{ $totalCount }} products</p>
                <div class="flex items-center gap-3">
                    <select class="border border-gray-200 rounded-md text-sm px-3 py-1.5 outline-none">
                        <option>Sort by: Popularity</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest</option>
                    </select>
                    <div class="flex border border-gray-200 rounded-md overflow-hidden">
                        <button class="w-8 h-8 flex items-center justify-center bg-brand-700 text-white"><i
                                class="fa-solid fa-grip"></i></button>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-500"><i
                                class="fa-solid fa-list"></i></button>
                    </div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-center gap-2 mt-10">
                <button class="w-9 h-9 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-50"><i
                        class="fa-solid fa-chevron-left text-xs"></i></button>
                @for ($i = 1; $i <= 3; $i++)
                    <button
                        class="w-9 h-9 rounded-md {{ $i === 1 ? 'bg-brand-700 text-white' : 'border border-gray-200 hover:bg-gray-50' }} text-sm font-medium">{{ $i }}</button>
                @endfor
                <span class="text-gray-400">...</span>
                <button class="w-9 h-9 rounded-md border border-gray-200 text-sm hover:bg-gray-50">12</button>
                <button class="w-9 h-9 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-50"><i
                        class="fa-solid fa-chevron-right text-xs"></i></button>
            </div>
        </div>
    </div>

@endsection
