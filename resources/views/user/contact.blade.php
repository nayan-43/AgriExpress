@extends('user.layouts.app')

@section('title', 'Contact Us — AgriExpress')

@section('content')

    <x-page-banner eyebrow="Get In Touch" heading="We'd Love to Hear<br>from You"
        subtitle="Have a question, feedback or need assistance? Our team is here to help. Reach out to us anytime."
        image="https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=1000&q=80" :crumbs="['Home' => route('index'), 'Contact' => null]" />

    <div class="max-w-7xl mx-auto px-4 py-12 grid lg:grid-cols-[1fr_380px] gap-8">

        {{-- ============ Message form ============ --}}
        <div class="border border-gray-100 rounded-xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Send Us a Message</h2>
            <p class="text-gray-500 text-sm mb-6">Fill out the form below and we'll get back to you as soon as possible.</p>

            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-md px-4 py-3 mb-6">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('status') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                            class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Your name"
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span
                            class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        placeholder="you@example.com"
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                        placeholder="+91 98765 43210"
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span
                            class="text-red-500">*</span></label>
                    <select id="subject" name="subject" required
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700 bg-white text-gray-600">
                        <option value="">Select a subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>
                        @endforeach
                    </select>
                    @error('subject')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span
                            class="text-red-500">*</span></label>
                    <textarea id="message" name="message" rows="5" required placeholder="Type your message here..."
                        class="w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700 resize-y">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium flex items-center justify-center gap-2">
                    Send Message <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </form>
        </div>

        {{-- ============ Sidebar: contact info, socials ============ --}}
        <aside class="space-y-6">
            <div class="border border-gray-100 rounded-xl p-6">
                <h2 class="font-bold text-gray-900 mb-1">Contact Information</h2>
                <p class="text-gray-500 text-sm mb-6">You can also reach us through the following channels.</p>

                <ul class="space-y-5">
                    @foreach ($contactChannels as $channel)
                        <li class="flex gap-3">
                            <span
                                class="w-9 h-9 shrink-0 rounded-full bg-brand-50 flex items-center justify-center text-brand-700">
                                <i class="{{ $channel['icon'] }}"></i>
                            </span>
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">{{ $channel['label'] }}</p>
                                @foreach ($channel['lines'] as $line)
                                    @if (!empty($channel['href']) && $loop->first)
                                        <a href="{{ $channel['href'] }}"
                                            class="text-gray-500 hover:text-brand-700 block">{{ $line }}</a>
                                    @else
                                        <p class="text-gray-500">{{ $line }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="border border-gray-100 rounded-xl p-6">
                <h2 class="font-bold text-gray-900 mb-1">Follow Us</h2>
                <p class="text-gray-500 text-sm mb-4">Stay connected for the latest updates, offers and more.</p>
                <div class="flex gap-3">
                    @foreach ($socials as $social)
                        <a href="{{ $social['url'] }}" aria-label="{{ $social['name'] }}"
                            class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 hover:bg-brand-700 hover:text-white transition">
                            <i class="{{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

    {{-- ============ Map ============ --}}
    <div class="max-w-7xl mx-auto px-4 pb-6">
        <div class="relative rounded-xl overflow-hidden border border-gray-100 h-80">
            <iframe src="{{ $mapEmbedUrl }}" class="w-full h-full border-0" allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade" title="AgriExpress store location"></iframe>

            {{-- Floating address card over the map --}}
            <div class="absolute top-6 left-6 bg-white rounded-lg shadow-lg px-5 py-4 max-w-xs">
                <p class="font-semibold text-gray-900 text-sm mb-1">{{ $storeName }}</p>
                <p class="text-xs text-gray-500 leading-relaxed">
                    {{ $storeAddress['line1'] }}<br>{{ $storeAddress['city'] }}</p>
            </div>
        </div>
    </div>

    {{-- ============ Support banner ============ --}}
    <div class="max-w-7xl mx-auto px-4 pb-12">
        <div class="bg-brand-50 rounded-xl px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span
                    class="w-12 h-12 shrink-0 rounded-full bg-white flex items-center justify-center text-brand-700 text-xl shadow-sm">
                    <i class="fa-solid fa-headset"></i>
                </span>
                <div>
                    <p class="font-semibold text-gray-900">Need Help?</p>
                    <p class="text-gray-500 text-sm">Our support team is available 24/7 to assist you with any questions or
                        concerns.</p>
                </div>
            </div>
            <button
                class="bg-brand-700 hover:bg-brand-800 text-white px-6 py-3 rounded-md font-medium whitespace-nowrap flex items-center gap-2">
                Chat With Us <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>
        </div>
    </div>

@endsection
