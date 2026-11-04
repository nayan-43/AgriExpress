@extends('user.layouts.app')

@section('title', $product['name'] . ' — AgriExpress')

@section('content')
    <style>
        .product-detail-grid {
            display: grid;
            gap: 2.5rem;
        }

        @media (min-width: 768px) {
            .product-detail-grid {
                grid-template-columns: minmax(0, 4fr) minmax(0, 6fr);
            }
        }

        .product-gallery-stage {
            position: relative;
            min-width: 0;
            max-width: 100%;
        }

        .product-gallery-main {
            width: min(100%, 400px);
            flex: 0 1 400px;
        }

        .product-gallery-actions {
            position: absolute;
            top: .75rem;
            right: .75rem;
            z-index: 3;
        }

        .product-gallery-action {
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: .65rem;
            background: rgba(255, 255, 255, .94);
            color: #374151;
            box-shadow: 0 4px 12px rgba(15, 23, 42, .12);
            transition: color .15s ease, background-color .15s ease, transform .15s ease;
        }

        .product-gallery-action:hover {
            color: #195c38;
            background: #eef6f0;
            transform: translateY(-1px);
        }

        .product-gallery-lens {
            display: none;
            position: absolute;
            width: 30%;
            height: 30%;
            /* border: 1px solid rgba(25, 92, 56, .75);
                background: rgba(255, 255, 255, .18); */
            background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyIiBoZWlnaHQ9IjIiIHZpZXdCb3g9IjAgMCAyIDIiPjx0aXRsZT5waXhlbDwvdGl0bGU+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9Ii4wNSIgZD0iTTAgMGgydjJIMHoiLz48cGF0aCBmaWxsPSIjOGRhY2RhIiBkPSJNMCAwaDF2MUgweiIvPjwvZz48L3N2Zz4=);
            cursor: crosshair;
            z-index: 2;
        }

        .product-gallery-preview {
            display: none;
            position: fixed;
            top: 115px;
            right: 10px;
            width: 55vw;
            height: calc(100vh - 125px);
            border: 2px solid #14472c;
            border-radius: .75rem;
            background-color: #f9fafb;
            background-repeat: no-repeat;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .12);
            pointer-events: none;
            z-index: 30;
        }

        .product-gallery-stage.is-previewing .product-gallery-lens,
        .product-gallery-stage.is-previewing .product-gallery-preview {
            display: block;
        }

        @media (max-width: 1023px) {
            .product-gallery-preview {
                display: none !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-5 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('status') }}
            </div>
        @endif

        <p class="text-xs text-gray-500 mb-6">
            <a href="{{ route('index') }}" class="hover:text-brand-700">Home</a> <i
                class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <a href="{{ route('shop') }}" class="hover:text-brand-700">Shop</a> <i
                class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <a href="{{ route('shop') }}?category={{ $product['category_slug'] }}"
                class="hover:text-brand-700">{{ $product['category'] }}</a> <i
                class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            @if ($product['brand'])
                <a href="{{ route('shop') }}?brand={{ $product['brand_slug'] }}"
                    class="hover:text-brand-700">{{ $product['brand'] }}</a> <i
                    class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            @endif
            <span class="text-gray-700">{{ $product['name'] }}</span>
        </p>

        <div class="product-detail-grid">
            {{-- Gallery --}}
            <div class="product-gallery-stage flex items-start gap-4">
                <div class="flex flex-col gap-3">
                    @foreach ($product['gallery'] as $i => $img)
                        <button type="button" data-gallery-thumb data-full-image="{{ $img }}"
                            aria-label="View {{ $product['name'] }} image {{ $i + 1 }}"
                            class="w-16 h-16 shrink-0 rounded-lg border-2 {{ $i === 0 ? 'border-brand-700' : 'border-gray-200' }} overflow-hidden bg-white">
                            <img src="{{ $img }}" alt="" class="w-full h-full object-contain">
                        </button>
                    @endforeach
                </div>
                <div data-gallery-zoom
                    class="product-gallery-main aspect-square rounded-xl overflow-hidden bg-gray-50 border border-gray-100 cursor-crosshair relative">
                    <img data-gallery-main src="{{ $product['gallery'][0] ?? asset('assets/images/placeholder.png') }}"
                        alt="{{ $product['name'] }}" class="w-full h-full object-contain">
                    <span data-gallery-lens class="product-gallery-lens"></span>
                    <div class="product-gallery-actions flex flex-col gap-2">
                        <form
                            action="{{ auth('web')->check() ? route('wishlist.toggle', $product['id']) : route('login') }}"
                            method="{{ auth('web')->check() ? 'POST' : 'GET' }}">
                            @if (auth('web')->check())
                                @csrf
                            @endif
                            <button type="submit" class="product-gallery-action"
                                aria-label="{{ $product['is_wishlisted'] ? 'Remove from wishlist' : 'Add to wishlist' }}"
                                title="{{ $product['is_wishlisted'] ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                @if ($product['is_wishlisted'])
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="#ef4444" stroke="none" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart preview-icon">
                                        <path
                                            d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-heart-plus preview-icon">
                                        <path
                                            d="m14.479 19.374-.971.939a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5a5.2 5.2 0 0 1-.219 1.49" />
                                        <path d="M15 15h6" />
                                        <path d="M18 12v6" />
                                    </svg>
                                @endif
                            </button>
                        </form>
                        <button type="button" data-share-product class="product-gallery-action" aria-label="Share product"
                            title="Share product">
                            {{-- <i class="fa-regular fa-paper-plane"></i> --}}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M10.125 13.875L15 9" stroke="#333333ff" stroke-width="1.4" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path
                                    d="M20.9716 3.95434C21.0078 3.82605 21.0091 3.69044 20.9754 3.56147C20.9417 3.4325 20.8743 3.31484 20.78 3.22059C20.6858 3.12633 20.5681 3.0589 20.4392 3.02524C20.3102 2.99157 20.1746 2.99288 20.0463 3.02903L2.04629 8.48716C1.89934 8.52863 1.76854 8.61397 1.67139 8.73177C1.57424 8.84958 1.51536 8.99422 1.50262 9.14639C1.48988 9.29855 1.52388 9.45098 1.6001 9.58329C1.67631 9.71561 1.79109 9.82151 1.9291 9.88684L10.1257 13.875L14.1138 22.0706C14.1791 22.2086 14.285 22.3234 14.4173 22.3996C14.5497 22.4758 14.7021 22.5098 14.8542 22.4971C15.0064 22.4843 15.1511 22.4255 15.2689 22.3283C15.3867 22.2312 15.472 22.1004 15.5135 21.9534L20.9716 3.95434Z"
                                    stroke="#333333ff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div data-gallery-preview class="product-gallery-preview"></div>
            </div>

            {{-- Info --}}
            <div>
                @if ($product['brand'])
                    <a href="{{ route('shop') }}?brand={{ $product['brand_slug'] }}"
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-700 hover:text-brand-800 mb-2">
                        <i class="fa-solid fa-tag text-xs"></i>{{ $product['brand'] }}
                    </a>
                @endif
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

                <form action="{{ route('cart.add', $product['id']) }}" method="POST" class="flex flex-col gap-3 mb-6"
                    style="width: 60%;">
                    @csrf
                    <div class="flex items-center border border-gray-200 rounded-md w-fit" data-qty data-min="1"
                        data-max="10">
                        <button type="button" data-qty-decrease class="w-9 h-9 text-gray-600 hover:bg-gray-50">−</button>
                        <span data-qty-value class="w-10 text-center text-sm">1</span>
                        <input type="hidden" name="quantity" value="1" data-qty-input>
                        <button type="button" data-qty-increase class="w-9 h-9 text-gray-600 hover:bg-gray-50">+</button>
                    </div>
                    <div class="flex items-center gap-3 mb-6">
                        <button type="submit"
                            class="flex-1 bg-brand-700 hover:bg-brand-800 text-white py-2.5 rounded-md font-medium flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                        </button>
                        <button type="submit" formaction="{{ route('buy.now', $product['id']) }}"
                            class="flex-1 text-center border border-brand-700 text-brand-700 hover:bg-brand-50 py-2.5 rounded-md font-medium">Buy
                            Now</button>
                    </div>
                </form>

                <div class="flex items-center gap-6 text-sm text-gray-600 mb-8">
                    <button class="hover:text-brand-700"><i class="fa-solid fa-code-compare"></i> Compare</button>
                </div>

                @if ($product['brand'])
                    <div class="flex items-center gap-3 border-y border-gray-100 py-4 mb-6">
                        @if ($product['brand_logo'])
                            <img src="{{ $product['brand_logo'] }}" alt="{{ $product['brand'] }}"
                                class="w-12 h-12 rounded-lg object-contain border border-gray-100 bg-white p-1.5">
                        @else
                            <div
                                class="w-12 h-12 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($product['brand'], 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500">Brand</p>
                            <a href="{{ route('shop') }}?brand={{ $product['brand_slug'] }}"
                                class="font-semibold text-gray-900 hover:text-brand-700">{{ $product['brand'] }}</a>
                        </div>
                    </div>
                @endif

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
                <p>{!! $product['long_description'] !!}</p>
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
