@extends('user.layouts.app')

@section('title', $product['name'] . ' — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <p class="text-xs text-gray-500 mb-6">
            <a href="{{ route('index') }}" class="hover:text-brand-700">Home</a> <i
                class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <a href="{{ route('shop') }}" class="hover:text-brand-700">Shop</a> <i
                class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <a href="{{ route('shop') }}?category={{ $product['category_slug'] }}"
                class="hover:text-brand-700">{{ $product['category'] }}</a> <i
                class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <span class="text-gray-700">{{ $product['name'] }}</span>
        </p>

        <div class="grid md:grid-cols-2 gap-10">
            {{-- Gallery --}}
            <div class="flex gap-4">
                <div class="flex flex-col gap-3">
                    @foreach ($product['gallery'] as $i => $img)
                        <button data-gallery-thumb data-full-image="{{ $img }}"
                            class="w-16 h-16 rounded-lg border-2 {{ $i === 0 ? 'border-brand-700' : 'border-gray-200' }} overflow-hidden">
                            <img src="{{ $img }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                <div class="flex-1 rounded-xl overflow-hidden bg-gray-50">
                    <img data-gallery-main src="{{ $product['gallery'][0] ?? asset('assets/images/placeholder.png') }}"
                        class="w-full h-full object-cover">
                </div>
            </div>

            {{-- Info --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product['name'] }}</h1>
                <div class="flex items-center gap-2 mb-4">
                    <x-star-rating :rating="$product['rating']" />
                    <span class="text-sm text-gray-500">({{ $product['reviews'] }} reviews)</span>
                </div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($product['price'], 2) }}</span>
                    @if ($product['old_price'])
                        <span
                            class="text-lg text-gray-400 line-through">${{ number_format($product['old_price'], 2) }}</span>
                        <span
                            class="bg-brand-100 text-brand-700 text-xs font-semibold px-2 py-1 rounded">{{ $product['discount_pct'] }}%
                            OFF</span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm mb-6 max-w-md">{{ $product['description'] }}</p>

                @if (!empty($product['colors']))
                    <div class="mb-5" data-swatch-wrap>
                        <p class="text-sm font-medium text-gray-800 mb-2">Color: <span data-swatch-label
                                class="text-gray-500 font-normal">{{ $product['colors'][0]['name'] }}</span></p>
                        <div class="flex gap-2" data-swatch-group>
                            @foreach ($product['colors'] as $i => $color)
                                <button data-swatch="{{ $color['name'] }}"
                                    class="w-8 h-8 rounded-full {{ $color['class'] }} ring-2 ring-offset-2 {{ $i === 0 ? 'ring-brand-700' : 'ring-transparent' }}"></button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <p class="text-emerald-600 text-sm font-medium mb-5"><i class="fa-solid fa-circle-check"></i> In Stock</p>

                <form action="{{ route('cart.add', $product['id']) }}" method="POST" class="flex flex-col gap-3 mb-6">
                    @csrf
                    <div class="flex items-center border border-gray-200 rounded-md w-fit" data-qty data-min="1"
                        data-max="10">
                        <button type="button" data-qty-decrease class="w-9 h-9 text-gray-600 hover:bg-gray-50">−</button>
                        <span data-qty-value class="w-10 text-center text-sm">1</span>
                        <button type="button" data-qty-increase class="w-9 h-9 text-gray-600 hover:bg-gray-50">+</button>
                    </div>
                    <div class="flex items-center gap-3 mb-6">
                        <button type="submit"
                            class="flex-1 bg-brand-700 hover:bg-brand-800 text-white py-2.5 rounded-md font-medium flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                        </button>
                        <a href="{{ route('checkout') }}"
                            class="flex-1 text-center border border-brand-700 text-brand-700 hover:bg-brand-50 py-2.5 rounded-md font-medium">Buy
                            Now</a>
                    </div>
                </form>

                <div class="flex items-center gap-6 text-sm text-gray-600 mb-8">
                    <form action="{{ auth('web')->check() ? route('wishlist.toggle', $product['id']) : route('login') }}"
                        method="{{ auth('web')->check() ? 'POST' : 'GET' }}">
                        @if (auth('web')->check())
                            @csrf
                        @endif
                        <button type="submit" class="hover:text-brand-700"><i class="fa-regular fa-heart"></i> Add to
                            Wishlist</button>
                    </form>
                    <button class="hover:text-brand-700"><i class="fa-solid fa-code-compare"></i> Compare</button>
                </div>

                <div class="grid grid-cols-3 gap-3 border-t border-gray-100 pt-6">
                    <div class="flex items-center gap-2 text-sm text-gray-600"><i
                            class="fa-solid fa-truck text-brand-700"></i><span>Free Shipping<br><span
                                class="text-xs text-gray-400">On orders over $50</span></span></div>
                    <div class="flex items-center gap-2 text-sm text-gray-600"><i
                            class="fa-solid fa-rotate-left text-brand-700"></i><span>30 Days Return<br><span
                                class="text-xs text-gray-400">Easy & free returns</span></span></div>
                    <div class="flex items-center gap-2 text-sm text-gray-600"><i
                            class="fa-solid fa-lock text-brand-700"></i><span>Secure Payment<br><span
                                class="text-xs text-gray-400">100% secure checkout</span></span></div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="mt-12" data-tabs>
            <div class="border-b border-gray-200 flex gap-8 text-sm font-medium">
                <button data-tab-btn="description"
                    class="pb-3 border-b-2 border-brand-700 text-brand-700">Description</button>
                <button data-tab-btn="specs"
                    class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Specifications</button>
                <button data-tab-btn="reviews"
                    class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Reviews
                    ({{ $product['reviews'] }})</button>
            </div>

            <div data-tab-panel="description" class="py-6 max-w-3xl text-gray-600 text-sm leading-relaxed">
                <p>{{ $product['long_description'] }}</p>
            </div>
            <div data-tab-panel="specs" class="py-6 max-w-3xl text-gray-600 text-sm leading-relaxed hidden">
                <ul class="divide-y divide-gray-100">
                    @foreach ($product['specs'] as $label => $value)
                        <li class="flex justify-between py-2"><span class="text-gray-500">{{ $label }}</span><span
                                class="text-gray-800 font-medium">{{ $value }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div data-tab-panel="reviews" class="py-6 max-w-3xl text-gray-600 text-sm leading-relaxed hidden">
                <p>{{ $product['reviews'] }} customers have reviewed this product with an average rating of
                    {{ $product['rating'] }} / 5.</p>
            </div>
        </div>

        {{-- Related --}}
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-5">You may also like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach ($related as $item)
                    <a href="{{ route('product', $item['slug'] ?? $item['id']) }}"
                        class="border border-gray-100 rounded-lg overflow-hidden block hover:shadow-md transition">
                        <img src="{{ $item['image'] }}" class="w-full h-40 object-cover">
                        <div class="p-3">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                            <span
                                class="font-semibold text-gray-900 text-sm">${{ number_format($item['price'], 2) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
