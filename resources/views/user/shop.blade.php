@extends('user.layouts.app')

@section('title', 'Shop — AgriExpress')

@push('styles')
    <style>
        /* ===== Modern price-range slider ===== */
        .shop-range {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 9999px;
            background: #e5e7eb;
            /* JS overwrites this with the live fill gradient */
            outline: none;
            cursor: pointer;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .08);
        }

        /* Firefox draws the filled portion natively via ::-moz-range-progress,
               so it doesn't need the JS-driven background gradient WebKit/Blink use. */
        .shop-range::-moz-range-track {
            height: 8px;
            border-radius: 9999px;
            background: #e5e7eb;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .08);
        }

        .shop-range::-moz-range-progress {
            height: 8px;
            border-radius: 9999px;
            background: linear-gradient(to right, #195c38, #2f8f5b);
        }

        .shop-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            margin-top: -8px;
            border-radius: 9999px;
            border: none;
            background: radial-gradient(circle at center, #195c38 0 7px, #ffffff 8px 100%);
            box-shadow: 0 0 0 4px rgba(25, 92, 56, .14), 0 3px 8px rgba(15, 55, 34, .3);
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .shop-range::-webkit-slider-thumb:hover {
            transform: scale(1.15);
            box-shadow: 0 0 0 6px rgba(25, 92, 56, .16), 0 4px 12px rgba(15, 55, 34, .35);
        }

        .shop-range:active::-webkit-slider-thumb {
            transform: scale(1.05);
        }

        .shop-range::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 9999px;
            border: none;
            background: radial-gradient(circle at center, #195c38 0 7px, #ffffff 8px 100%);
            box-shadow: 0 0 0 4px rgba(25, 92, 56, .14), 0 3px 8px rgba(15, 55, 34, .3);
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .shop-range::-moz-range-thumb:hover {
            transform: scale(1.15);
        }

        .shop-range:focus-visible {
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .08), 0 0 0 4px rgba(25, 92, 56, .15);
        }

        /* Floating value bubble that tracks the thumb */
        .shop-range-bubble {
            position: absolute;
            top: -2px;
            transform: translate(-50%, -100%);
            white-space: nowrap;
            pointer-events: none;
        }

        .shop-range-bubble::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #195c38;
        }
    </style>
@endpush

@section('content')

    {{-- Small page banner (not full-bleed hero — a lightweight section banner) --}}
    <section class="bg-brand-50">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <p class="text-xs text-gray-500 mb-2">
                <a href="{{ route('index') }}" class="hover:text-brand-700">Home</a>
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
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900">Price Range</h3>
                    <span class="text-xs text-gray-400">Up to $500</span>
                </div>

                @php $maxPrice = (int) request('max_price', 500); @endphp

                <div class="relative pt-7 px-1" data-price-wrap>
                    <span data-price-bubble
                        class="shop-range-bubble bg-brand-700 text-white text-[11px] font-semibold px-2 py-1 rounded-md shadow-sm"
                        style="left: {{ ($maxPrice / 500) * 100 }}%">
                        <span data-price-value>${{ $maxPrice }}</span>
                    </span>

                    <input type="range" min="0" max="500" step="5" value="{{ $maxPrice }}"
                        name="max_price" form="shop-filter-form" class="shop-range" data-shop-control data-price-range
                        style="background: linear-gradient(to right, #195c38 {{ ($maxPrice / 500) * 100 }}%, #e5e7eb {{ ($maxPrice / 500) * 100 }}%)">
                </div>

                <div class="flex justify-between text-[11px] text-gray-400 mt-2">
                    <span>$0</span>
                    <span>$500</span>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Brand</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    @foreach ($brands as $brand)
                        <label class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><input type="checkbox" name="brand[]"
                                    value="{{ $brand['slug'] }}" form="shop-filter-form" class="accent-brand-700"
                                    data-shop-control @checked(in_array($brand['slug'], (array) request('brand', []), true))> {{ $brand['name'] }}</span>
                            <span class="text-gray-400">{{ $brand['count'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Product grid --}}
        <div>
            <form id="shop-filter-form" action="{{ route('shop') }}" method="GET" data-shop-filter
                data-shop-target="shop-results" class="mb-5 flex flex-wrap justify-end gap-3">
                @if (request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <select name="sort" class="border border-gray-200 rounded-md text-sm px-3 h-10 outline-none">
                    <option value="">Newest</option>
                    <option value="name" @selected(request('sort') === 'name')>Name A-Z</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                </select>
            </form>
            <div id="shop-results">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-sm text-gray-500">{{ $totalCount }} products</p>
                    <div class="flex items-center gap-3">
                        <div class="flex border border-gray-200 rounded-md overflow-hidden">
                            <button class="w-8 h-8 flex items-center justify-center bg-brand-700 text-white"><i
                                    class="fa-solid fa-grip"></i></button>
                            <button class="w-8 h-8 flex items-center justify-center text-gray-500"><i
                                    class="fa-solid fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
                    @forelse ($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div
                            class="sm:col-span-2 md:col-span-3 border border-dashed border-gray-200 rounded-lg py-16 text-center">
                            <i class="fa-solid fa-magnifying-glass text-3xl text-gray-300 mb-3"></i>
                            <h2 class="font-semibold text-gray-900 mb-1">No products found</h2>
                            <p class="text-sm text-gray-500">Try a different search or adjust your filters.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-center gap-2 mt-10">
                    @if ($productPaginator->onFirstPage())
                        <span
                            class="w-9 h-9 rounded-md border border-gray-100 text-gray-300 flex items-center justify-center"><i
                                class="fa-solid fa-chevron-left text-xs"></i></span>
                    @else
                        <a href="{{ $productPaginator->previousPageUrl() }}"
                            class="w-9 h-9 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center"><i
                                class="fa-solid fa-chevron-left text-xs"></i></a>
                    @endif
                    @for ($page = 1; $page <= $productPaginator->lastPage(); $page++)
                        <a href="{{ $productPaginator->url($page) }}"
                            class="w-9 h-9 rounded-md {{ $page === $productPaginator->currentPage() ? 'bg-brand-700 text-white' : 'border border-gray-200 hover:bg-gray-50' }} text-sm font-medium flex items-center justify-center">{{ $page }}</a>
                    @endfor
                    @if ($productPaginator->hasMorePages())
                        <a href="{{ $productPaginator->nextPageUrl() }}"
                            class="w-9 h-9 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-50 flex items-center justify-center"><i
                                class="fa-solid fa-chevron-right text-xs"></i></a>
                    @else
                        <span
                            class="w-9 h-9 rounded-md border border-gray-100 text-gray-300 flex items-center justify-center"><i
                                class="fa-solid fa-chevron-right text-xs"></i></span>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('[data-shop-filter]');
            if (!form) return;

            // ---- Price range: live fill + floating bubble ----
            const priceInput = document.querySelector('[data-price-range]');
            const priceValue = document.querySelector('[data-price-value]');
            const priceBubble = document.querySelector('[data-price-bubble]');

            const paintPriceRange = function() {
                if (!priceInput) return;
                const min = Number(priceInput.min || 0);
                const max = Number(priceInput.max || 100);
                const pct = ((priceInput.value - min) / (max - min)) * 100;

                priceInput.style.background =
                    `linear-gradient(to right, #195c38 ${pct}%, #e5e7eb ${pct}%)`;
                if (priceValue) priceValue.textContent = '$' + priceInput.value;
                if (priceBubble) priceBubble.style.left = pct + '%';
            };

            priceInput?.addEventListener('input', paintPriceRange);
            paintPriceRange(); // sync on load in case the browser restored a cached slider position

            // ---- Filter form: submit on change, swap results via fetch ----
            const targetId = form.dataset.shopTarget;
            const refresh = function(url) {
                const target = document.getElementById(targetId);
                if (!target) return form.submit();
                target.classList.add('opacity-50', 'pointer-events-none');
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Request failed');
                        return response.text();
                    })
                    .then(html => {
                        const replacement = new DOMParser().parseFromString(html, 'text/html')
                            .getElementById(targetId);
                        if (!replacement) throw new Error('Results not found');
                        target.replaceWith(replacement);
                        window.history.replaceState({}, '', url);
                        bindPagination();
                    })
                    .catch(() => {
                        window.location.href = url;
                    });
            };
            const submit = function() {
                refresh(new URL(form.action + '?' + new URLSearchParams(new FormData(form)), window.location
                    .origin));
            };
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                submit();
            });
            form.querySelectorAll('select').forEach(select => select.addEventListener('change', submit));
            document.querySelectorAll('[data-shop-control]').forEach(control => control.addEventListener('change',
                submit));
            const bindPagination = function() {
                document.querySelectorAll('#' + targetId + ' a[href]').forEach(link => link.addEventListener(
                    'click',
                    function(event) {
                        event.preventDefault();
                        refresh(link.href);
                    }));
            };
            bindPagination();
        });
    </script>
@endpush
