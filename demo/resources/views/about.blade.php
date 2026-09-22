@extends('layouts.app')

@section('title', 'About Us — AgriExpress')

@section('content')

    <x-page-banner eyebrow="About Us" heading="Our Story<br>Your Trusted Store"
        subtitle="At AgriExpress, we believe that great products bring happiness to everyday life. We're more than just an online store — we're a community that values quality, style and your satisfaction."
        image="https://images.unsplash.com/photo-1567016432779-094069958ea5?w=1000&q=80" :crumbs="['Home' => route('home'), 'About Us' => null]"
        :cta="['label' => 'Shop Now', 'url' => route('shop')]" />

    {{-- ============ Value props strip ============ --}}
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach ($valueProps as $prop)
                <div class="flex flex-col items-center gap-3">
                    <span class="w-14 h-14 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 text-xl">
                        <i class="{{ $prop['icon'] }}"></i>
                    </span>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $prop['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $prop['subtitle'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ Mission — image left, copy + stats right ============ --}}
    <section class="max-w-7xl mx-auto px-4 pb-12">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="relative rounded-xl overflow-hidden">
                <img src="{{ $mission['image'] }}" alt="Our mission" class="w-full h-80 object-cover rounded-xl">
                <div class="absolute bottom-5 left-5 bg-white rounded-lg shadow-md px-4 py-3 flex items-center gap-2">
                    <span class="text-rose-500"><i class="fa-solid fa-heart"></i></span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-none">Better</p>
                        <p class="text-sm font-semibold text-gray-900 leading-none">Products</p>
                        <p class="text-sm font-semibold text-gray-900 leading-none">Happier You</p>
                    </div>
                </div>
            </div>
            <div>
                <p class="uppercase tracking-wide text-xs font-semibold text-brand-700 mb-3">Our Mission</p>
                <h2 class="text-3xl font-bold text-gray-900 leading-tight mb-4">{!! $mission['heading'] !!}</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-8">{{ $mission['body'] }}</p>

                <div class="grid grid-cols-3 gap-6 border-t border-gray-100 pt-6">
                    @foreach ($mission['stats'] as $stat)
                        <div>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $stat['value'] }}@if (!empty($stat['star']))
                                    <i class="fa-solid fa-star text-amber-400 text-lg ml-1"></i>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ Why choose us — tinted panel ============ --}}
    <section class="bg-brand-50 py-14">
        <div class="max-w-7xl mx-auto px-4">
            <p class="uppercase tracking-wide text-xs font-semibold text-brand-700 mb-2">Why Choose Us</p>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-10">What Makes Us Different</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @foreach ($differentiators as $item)
                    <div class="flex flex-col items-center gap-3">
                        <span
                            class="w-14 h-14 rounded-full bg-white flex items-center justify-center text-brand-700 text-xl shadow-sm">
                            <i class="{{ $item['icon'] }}"></i>
                        </span>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $item['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-1 max-w-48 mx-auto">{{ $item['subtitle'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
