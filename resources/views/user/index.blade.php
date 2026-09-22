@extends('user.layouts.app')

@section('title', 'AgriExpress — Discover Your New Favorites')

@push('styles')
    <style>
        /* ---------- HERO ---------- */
        .se-hero {
            position: relative;
            overflow: hidden;
        }

        .se-hero__track {
            display: flex;
            transition: transform .5s ease;
            will-change: transform;
        }

        .se-hero__slide {
            flex: 0 0 100%;
            width: 100%;
        }

        .se-hero__inner {
            display: grid;
            grid-template-columns: 1fr;
            /* mobile: stacked */
            align-items: stretch;
            min-height: 22rem;
        }

        .se-hero__content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .se-hero__eyebrow {
            text-transform: uppercase;
            letter-spacing: .05em;
            font-size: .8125rem;
            font-weight: 600;
            color: #195c38;
            margin-bottom: .75rem;
        }

        .se-hero__title {
            font-size: 2.25rem;
            line-height: 1.1;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
        }

        .se-hero__subtitle {
            color: #4b5563;
            margin-bottom: 1.5rem;
            max-width: 28rem;
        }

        .se-hero__cta {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            width: fit-content;
            background: #195c38;
            color: #fff;
            padding: .75rem 1.5rem;
            border-radius: .375rem;
            font-weight: 500;
        }

        .se-hero__cta:hover {
            background: #14472c;
        }

        /* Image column: fills its cell completely — no rounding, no inset */
        .se-hero__media {
            position: relative;
            min-height: 16rem;
        }

        .se-hero__media img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .se-hero__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, .9);
            color: #374151;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
            z-index: 10;
            cursor: pointer;
        }

        .se-hero__arrow:hover {
            background: #fff;
        }

        .se-hero__arrow--prev {
            left: 1rem;
        }

        .se-hero__arrow--next {
            right: 1rem;
        }

        .se-hero__dots {
            position: absolute;
            bottom: 1.5rem;
            left: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            z-index: 10;
        }

        @media (min-width: 768px) {
            .se-hero__inner {
                grid-template-columns: 1fr 1fr;
                min-height: 28rem;
            }

            .se-hero__content {
                padding: 0 2rem 0 4rem;
            }

            .se-hero__title {
                font-size: 3rem;
            }

            .se-hero__media {
                min-height: 0;
            }

            .se-hero__arrow {
                display: flex;
            }

            .se-hero__dots {
                left: 4rem;
            }
        }

        @media (min-width: 1024px) {
            .se-hero__content {
                padding-left: 6rem;
            }

            .se-hero__dots {
                left: 6rem;
            }
        }

        /* ---------- GENERIC SLIDER (testimonials) ---------- */
        .se-slider {
            position: relative;
        }

        .se-slider__viewport {
            overflow: hidden;
        }

        .se-slider__track {
            display: flex;
            transition: transform .5s ease;
            will-change: transform;
        }

        /* mobile: 1 card per view */
        .se-slider__slide {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 .5rem;
            box-sizing: border-box;
            display: flex;
        }

        .se-slider__slide>* {
            width: 100%;
        }

        .se-slider__arrow {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            background: #fff;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
            z-index: 10;
            cursor: pointer;
        }

        .se-slider__arrow--prev {
            left: -.25rem;
        }

        .se-slider__arrow--next {
            right: -.25rem;
        }

        .se-slider__dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            margin-top: 1.5rem;
        }

        /* desktop: 3 cards per view */
        @media (min-width: 768px) {
            .se-slider__slide {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
                padding: 0 .75rem;
            }

            .se-slider__arrow--prev {
                left: -1.25rem;
            }

            .se-slider__arrow--next {
                right: -1.25rem;
            }
        }
    </style>
@endpush


@section('content')

    {{-- ============ HERO — full-bleed carousel ============ --}}
    {{-- All layout is in the scoped CSS at the bottom of this file so it does not
     depend on Tailwind responsive variants resolving in your build. --}}
    <section class="se-hero" data-carousel data-autoplay="6000">
        <div class="se-hero__track" data-carousel-track>
            @foreach ($heroSlides as $slide)
                <div class="se-hero__slide {{ $slide['bg'] }}" data-carousel-slide>
                    <div class="se-hero__inner">
                        <div class="se-hero__content">
                            <p class="se-hero__eyebrow">{{ $slide['eyebrow'] }}</p>
                            <h1 class="se-hero__title">{!! $slide['title'] !!}</h1>
                            <p class="se-hero__subtitle">{{ $slide['subtitle'] }}</p>
                            <a href="{{ route('shop') }}" class="se-hero__cta">
                                {{ $slide['cta'] }} <i class="fa-solid fa-arrow-right text-sm"></i>
                            </a>
                        </div>
                        <div class="se-hero__media">
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="se-hero__arrow se-hero__arrow--prev" data-carousel-prev aria-label="Previous slide">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button type="button" class="se-hero__arrow se-hero__arrow--next" data-carousel-next aria-label="Next slide">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <div class="se-hero__dots" data-carousel-dots></div>
    </section>

    {{-- ============ Categories ============ --}}
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-4 sm:grid-cols-8 gap-6 text-center">
            @foreach ($categories as $cat)
                <a href="{{ route('shop') }}?category={{ $cat['slug'] }}" class="flex flex-col items-center gap-2 group">
                    <span
                        class="w-16 h-16 rounded-full {{ $cat['bg'] }} flex items-center justify-center {{ $cat['color'] }} text-xl group-hover:opacity-80">
                        <i class="{{ $cat['icon'] }}"></i>
                    </span>
                    <span class="text-xs font-medium text-gray-700">{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ============ New Arrivals ============ --}}
    <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">New Arrivals</h2>
                <p class="text-gray-500 text-sm">Fresh styles. Latest trends. Just for you.</p>
            </div>
            <a href="{{ route('shop') }}" class="text-brand-700 text-sm font-medium hover:underline">View All <i
                    class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
            @foreach ($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    {{-- ============ Promo banners — two-column, hero-style full-bleed image ============ --}}
    {{-- Same visual treatment as the hero (image fills the card edge-to-edge,
     text overlaid with a gradient) but laid out as two cards side by side. --}}
    <section class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-2 gap-6">
        @foreach ($promoBanners as $banner)
            <div class="relative rounded-xl overflow-hidden min-h-60 flex items-center">
                <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}"
                    class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent"></div>

                <div class="relative z-10 px-6 sm:px-8 py-8">
                    <p class="text-white/80 text-sm mb-1">{{ $banner['eyebrow'] }}</p>
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $banner['title'] }}</h3>
                    <p class="text-white/80 text-sm mb-5">{{ $banner['subtitle'] }}</p>
                    <a href="{{ route('shop') }}?category={{ $banner['slug'] }}"
                        class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-900 px-5 py-2.5 rounded-md text-sm font-medium">
                        {{ $banner['cta'] }} <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </section>

    {{-- ============ Best Sellers ============ --}}
    <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Best Sellers</h2>
                <p class="text-gray-500 text-sm">Loved by customers. Always in demand.</p>
            </div>
            <a href="{{ route('shop') }}" class="text-brand-700 text-sm font-medium hover:underline">View All <i
                    class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
            @foreach ($bestSellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

@endsection
