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
            transition: left .12s ease;
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

        .product-grid {
            display: grid;
            gap: 1.25rem;
        }

        .product-grid.view-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .product-grid.view-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .product-grid-empty {
            grid-column: 1 / -1;
        }

        .shop-category-link {
            padding: .35rem .5rem;
            border-radius: .4rem;
            color: #64748b;
            transition: color .15s ease, background-color .15s ease;
        }

        .shop-category-link:hover,
        .shop-category-link.is-active {
            color: #195c38;
            background: #eef6f0;
        }

        .shop-category-link.is-active {
            font-weight: 600;
        }

        .shop-filter-sidebar {
            position: sticky;
            top: 7rem;
            max-height: calc(100vh - 8rem);
            overflow-y: auto;
            padding-right: .5rem;
            scrollbar-width: thin;
            scrollbar-color: #c9d2e0 transparent;
        }

        .shop-filter-sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .shop-filter-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .shop-filter-sidebar::-webkit-scrollbar-thumb {
            background: #c9d2e0;
            border-radius: 8px;
        }

        .shop-filter-sidebar::-webkit-scrollbar-thumb:hover {
            background: #aebccd;
        }

        @media (max-width: 1023px) {
            .shop-filter-sidebar {
                position: static;
                max-height: none;
                overflow-y: visible;
                padding-right: 0;
            }
        }

        .product-card {
            min-height: 100%;
            transition: box-shadow .2s ease;
            border-color: #195c3847;
        }

        .product-card:hover {
            /* transform: translateY(-4px); */
            box-shadow: 0 14px 28px rgba(15, 55, 34, .12);
        }

        .product-card-body>.flex.items-baseline {
            margin-top: auto;
            padding-top: .75rem;
        }

        .grid-view-button {
            color: #64748b;
            background: #ffffff;
            transition: color .15s ease, background-color .15s ease;
        }

        .grid-view-button:hover {
            color: #195c38;
            background: #eef6f0;
        }

        .grid-view-button.is-active {
            color: #ffffff;
            background: #195c38;
        }

        @media (min-width: 768px) {
            .product-grid.view-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .product-grid.view-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .shop-results-loading {
            position: absolute;
            inset: 0;
            z-index: 10;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 7rem;
            background: rgba(255, 255, 255, .72);
        }

        .shop-results-loading-spinner {
            width: 2rem;
            height: 2rem;
            border: 3px solid rgba(25, 92, 56, .2);
            border-top-color: #195c38;
            border-radius: 9999px;
            animation: shop-spin .7s linear infinite;
        }

        @keyframes shop-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')

    {{-- Small page banner (not full-bleed hero — a lightweight section banner) --}}
    <section class="" style="background: url('{{ asset('assets/images/shop_banner.png') }}');background-size: cover; background-position: center; background-repeat: no-repeat;min-height: 260px;">
        <div class="max-w-7xl mx-auto my-auto px-4 py-10">
            <p class="text-xs text-gray-500 mb-2">
                <a href="{{ route('index') }}" class="hover:text-brand-700">Home</a>
                <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i> Shop
            </p>
            <h1 class="text-3xl font-bold text-gray-900 mb-1 mt-3">Shop Our Collection</h1>
            <p class="text-gray-500 text-sm">Discover top quality products at the best prices.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[260px_1fr] gap-8">

        {{-- Sidebar filters --}}
        <aside class="shop-filter-sidebar space-y-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Categories</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>
                        <a href="{{ route('shop') }}"
                            class="shop-category-link flex items-center justify-between gap-3 {{ !request('category') ? 'is-active' : '' }}">
                            <span class="flex min-w-0 items-center gap-2">
                                {{-- <i class="{{ $categoryIcons['all'] }} w-4 text-center text-xs"></i> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-grid-2x2 preview-icon">
                                    <path d="M12 3v18" />
                                    <path d="M3 12h18" />
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                </svg>
                                <span>All Categories</span>
                            </span>
                            <span>{{ $totalCount }}</span>
                        </a>
                    </li>
                    @foreach ($filterCategories as $cat)
                        <li>
                            <a href="{{ route('shop') }}?category={{ $cat['slug'] }}"
                                class="shop-category-link flex items-center justify-between gap-3 {{ request('category') === $cat['slug'] ? 'is-active' : '' }}">
                                <span class="flex min-w-0 items-center gap-2">
                                        {!! $cat['svg_icon'] ?? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag preview-icon"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>' !!}
                                    <span class="truncate">{{ $cat['name'] }}</span>
                                </span>
                                <span>{{ $cat['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900">Price Range</h3>
                    <span class="text-xs text-gray-400">Up to $10000</span>
                </div>

                @php $maxPrice = (int) request('max_price', 10000); @endphp

                <div class="relative pt-7 px-1" data-price-wrap>
                    <span data-price-bubble
                        class="shop-range-bubble bg-brand-700 text-white text-[11px] font-semibold px-2 py-1 rounded-md shadow-sm"
                        style="left: {{ ($maxPrice / 10000) * 100 }}%">
                        <span data-price-value>${{ $maxPrice }}</span>
                    </span>

                    <input type="range" min="0" max="10000" step="10" value="{{ $maxPrice }}"
                        name="max_price" form="shop-filter-form" class="shop-range" data-shop-control data-price-range
                        style="background: linear-gradient(to right, #195c38 {{ ($maxPrice / 10000) * 100 }}%, #e5e7eb {{ ($maxPrice / 10000) * 100 }}%)">
                </div>

                <div class="flex justify-between text-[11px] text-gray-400 mt-2">
                    <span>$0</span>
                    <span>$10000</span>
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
            <div id="shop-results" class="relative">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-sm text-gray-500">{{ $totalCount }} products</p>
                    <div class="flex items-center gap-3">
                        <form id="shop-filter-form" action="{{ route('shop') }}" method="GET" data-shop-filter
                            data-shop-target="shop-results" class="">
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
                        <div class="flex border border-gray-200 rounded-lg overflow-hidden" data-grid-controls>
                            <button type="button" data-grid-view="3" aria-label="Show three columns"
                                class="grid-view-button w-9 h-9 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-grid-2x2 preview-icon">
                                    <path d="M12 3v18" />
                                    <path d="M3 12h18" />
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                </svg>
                            </button>
                            <button type="button" data-grid-view="4" aria-label="Show four columns"
                                class="grid-view-button w-9 h-9 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-grid-3x3 preview-icon">
                                    <rect width="18" height="18" x="3" y="3" rx="2" />
                                    <path d="M3 9h18" />
                                    <path d="M3 15h18" />
                                    <path d="M9 3v18" />
                                    <path d="M15 3v18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-grid view-3" data-product-grid>
                    @forelse ($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <div class="product-grid-empty border border-dashed border-gray-200 rounded-lg py-16 text-center">
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
                if (priceBubble) {
                    const wrapper = priceInput.closest('[data-price-wrap]');
                    const thumbWidth = 24;
                    const trackWidth = priceInput.getBoundingClientRect().width;
                    const inputLeft = priceInput.offsetLeft;
                    const thumbCenter = inputLeft + (trackWidth - thumbWidth) * (pct / 100) + (thumbWidth / 2);
                    priceBubble.style.left = thumbCenter + 'px';
                    priceBubble.style.top = '7px';
                }
            };

            priceInput?.addEventListener('input', paintPriceRange);
            paintPriceRange(); // sync on load in case the browser restored a cached slider position

            const initGridControls = function() {
                const grid = document.querySelector('[data-product-grid]');
                const controls = document.querySelector('[data-grid-controls]');
                if (!grid || !controls) return;
                const savedView = localStorage.getItem('shop-grid-view') || '3';
                const setView = function(view) {
                    grid.classList.toggle('view-3', view === '3');
                    grid.classList.toggle('view-4', view === '4');
                    controls.querySelectorAll('[data-grid-view]').forEach(button => {
                        const active = button.dataset.gridView === view;
                        button.classList.toggle('is-active', active);
                    });
                    localStorage.setItem('shop-grid-view', view);
                };
                controls.querySelectorAll('[data-grid-view]').forEach(button => {
                    button.addEventListener('click', () => setView(button.dataset.gridView));
                });
                setView(savedView);
            };
            initGridControls();

            // ---- Filter form: submit on change, swap results via fetch ----
            const targetId = form.dataset.shopTarget;
            const refresh = function(url) {
                const target = document.getElementById(targetId);
                if (!target) return form.submit();
                target.setAttribute('aria-busy', 'true');
                const loader = document.createElement('div');
                loader.className = 'shop-results-loading';
                loader.innerHTML =
                    '<span class="shop-results-loading-spinner" role="status" aria-label="Loading products"></span>';
                target.appendChild(loader);
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
                        initGridControls();
                    })
                    .catch(() => {
                        window.location.href = url;
                    })
                    .finally(() => {
                        target.removeAttribute('aria-busy');
                        loader.remove();
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
