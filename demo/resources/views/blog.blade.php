@extends('layouts.app')

@section('title', 'Blog — AgriExpress')

@section('content')

    <x-page-banner eyebrow="Our Blog" heading="Tips, Trends &amp; Stories"
        subtitle="Discover the latest trends, shopping tips, and inspiring stories from the AgriExpress community."
        image="https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=1000&q=80" :crumbs="['Home' => route('home'), 'Blog' => null]" />

    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Filter pills + search --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($blogCategories as $cat)
                    <a href="{{ route('blog') }}{{ $cat['slug'] ? '?category=' . $cat['slug'] : '' }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition
                  {{ $activeCategory === $cat['slug']
                      ? 'bg-brand-700 text-white'
                      : 'border border-gray-200 text-gray-600 hover:border-brand-700 hover:text-brand-700' }}">
                        {{ $cat['name'] }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('blog') }}" method="GET"
                class="flex items-center bg-gray-100 rounded-full px-4 py-2 w-full md:w-64">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search blog posts..."
                    class="bg-transparent outline-none text-sm w-full">
                <button type="submit"><i class="fa-solid fa-magnifying-glass text-gray-500"></i></button>
            </form>
        </div>

        {{-- Post grid --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <article class="border border-gray-100 rounded-lg overflow-hidden flex flex-col hover:shadow-md transition">
                    <div class="relative">
                        <span
                            class="absolute top-3 left-3 {{ $post['tag_color'] }} text-white text-[10px] font-semibold px-2.5 py-1 rounded">{{ $post['tag'] }}</span>
                        <button data-wishlist-btn
                            class="absolute top-3 right-3 w-7 h-7 rounded-full bg-white/90 flex items-center justify-center text-gray-500 hover:text-rose-500">
                            <i class="fa-regular fa-bookmark text-xs"></i>
                        </button>
                        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="w-full h-44 object-cover">
                    </div>

                    <div class="p-4 flex flex-col flex-1">
                        <h2 class="font-semibold text-gray-900 leading-snug mb-2">
                            <a href="{{ route('blog.show', $post['slug']) }}"
                                class="hover:text-brand-700">{{ $post['title'] }}</a>
                        </h2>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4 flex-1">{{ $post['excerpt'] }}</p>

                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $post['author_avatar'] }}" class="w-7 h-7 rounded-full object-cover">
                                <div class="leading-tight">
                                    <p class="text-xs font-medium text-gray-800">{{ $post['author'] }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $post['date'] }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $post['read_time'] }}</span>
                        </div>

                        <a href="{{ route('blog.show', $post['slug']) }}"
                            class="text-brand-700 text-sm font-medium hover:underline">
                            Read More <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-center gap-2 mt-10">
            <button class="w-9 h-9 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-50"><i
                    class="fa-solid fa-chevron-left text-xs"></i></button>
            @for ($i = 1; $i <= 5; $i++)
                <button
                    class="w-9 h-9 rounded-md text-sm font-medium {{ $i === $currentPage ? 'bg-brand-700 text-white' : 'border border-gray-200 hover:bg-gray-50' }}">{{ $i }}</button>
            @endfor
            <button class="w-9 h-9 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-50"><i
                    class="fa-solid fa-chevron-right text-xs"></i></button>
        </div>

        {{-- Inline newsletter banner (small, tinted, separate from the footer band) --}}
        <div
            class="bg-brand-50 rounded-xl px-8 py-8 mt-12 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="relative z-10">
                <p class="uppercase tracking-wide text-xs font-semibold text-brand-700 mb-1">Stay Updated</p>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Get the Latest News &amp; Offers</h3>
                <p class="text-gray-500 text-sm max-w-sm">Subscribe to our newsletter and be the first to know about new
                    products, discounts and stories.</p>
            </div>
            <form action="{{ route('newsletter.subscribe') }}" method="POST"
                class="flex gap-2 w-full md:w-auto relative z-10">
                @csrf
                <input type="email" name="email" required placeholder="Enter your email address"
                    class="rounded-md px-4 py-2.5 text-sm border border-gray-200 outline-none focus:border-brand-700 w-full md:w-64">
                <button
                    class="bg-brand-700 hover:bg-brand-800 text-white px-5 py-2.5 rounded-md text-sm font-medium whitespace-nowrap">Subscribe</button>
            </form>
            <i class="fa-solid fa-paper-plane absolute right-6 bottom-4 text-brand-700/10 text-7xl pointer-events-none"></i>
        </div>
    </div>

@endsection
