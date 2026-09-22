@extends('user.layouts.app')

@section('title', $post['title'] . ' — AgriExpress')

@section('content')
    <article class="max-w-3xl mx-auto px-4 py-10">
        <p class="text-xs text-gray-500 mb-4">
            <a href="{{ route('index') }}" class="hover:text-brand-700">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <a href="{{ route('blog') }}" class="hover:text-brand-700">Blog</a>
            <i class="fa-solid fa-chevron-right text-[8px] mx-1"></i>
            <span class="text-gray-700">{{ $post['tag'] }}</span>
        </p>

        <span
            class="inline-block {{ $post['tag_color'] }} text-white text-[10px] font-semibold px-2.5 py-1 rounded mb-4">{{ $post['tag'] }}</span>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">{{ $post['title'] }}</h1>

        <div class="flex items-center gap-3 mb-8">
            <img src="{{ $post['author_avatar'] }}" class="w-10 h-10 rounded-full object-cover">
            <div class="leading-tight">
                <p class="text-sm font-medium text-gray-800">{{ $post['author'] }}</p>
                <p class="text-xs text-gray-400">{{ $post['date'] }} &bull; {{ $post['read_time'] }}</p>
            </div>
        </div>

        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="w-full h-80 object-cover rounded-xl mb-8">

        <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed space-y-4">
            <p class="text-lg text-gray-700">{{ $post['excerpt'] }}</p>
            <p>Replace this block with your real post body — for example a <code>body</code> column rendered
                with <code></code>, or Markdown passed through a parser.</p>
            {{-- {!! $post['body'] !!} --}}
        </div>

        <div class="flex items-center gap-3 mt-10 pt-6 border-t border-gray-100">
            <span class="text-sm text-gray-500">Share:</span>
            <a href="#"
                class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 hover:bg-brand-700 hover:text-white transition"><i
                    class="fa-brands fa-facebook-f"></i></a>
            <a href="#"
                class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 hover:bg-brand-700 hover:text-white transition"><i
                    class="fa-brands fa-twitter"></i></a>
            <a href="#"
                class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 hover:bg-brand-700 hover:text-white transition"><i
                    class="fa-brands fa-pinterest"></i></a>
            <a href="#"
                class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 hover:bg-brand-700 hover:text-white transition"><i
                    class="fa-solid fa-link"></i></a>
        </div>
    </article>

    <section class="max-w-7xl mx-auto px-4 pb-12">
        <h2 class="text-xl font-bold text-gray-900 mb-5">Related Posts</h2>
        <div class="grid sm:grid-cols-3 gap-6">
            @foreach ($related as $item)
                <a href="{{ route('blog.show', $item['slug']) }}"
                    class="border border-gray-100 rounded-lg overflow-hidden block hover:shadow-md transition">
                    <img src="{{ $item['image'] }}" class="w-full h-36 object-cover">
                    <div class="p-4">
                        <span class="text-[10px] font-semibold text-brand-700">{{ $item['tag'] }}</span>
                        <p class="text-sm font-medium text-gray-800 leading-snug mt-1">{{ $item['title'] }}</p>
                        <p class="text-[10px] text-gray-400 mt-2">{{ $item['date'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
