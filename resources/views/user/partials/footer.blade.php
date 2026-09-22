@if (!isset($hideNewsletter))
    <section class="text-white"
        style="background: url({{ asset('assets/images/newsletter_banner.png') }});background-size: cover;">
        <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-leaf text-2xl"></i>
                <div>
                    <p class="font-semibold">Join Our Newsletter</p>
                    <p class="text-sm text-brand-100">Get the latest updates on new products, special offers and more.
                    </p>
                </div>
            </div>
            <form action="{{ url('/newsletter') }}" method="POST" class="flex w-full md:w-auto gap-2 me-40">
                @csrf
                <input type="email" name="email" required placeholder="Enter your email address"
                    class="rounded-md px-4 py-2 bg-brand-50 text-gray-800 w-full md:w-72 outline-none">
                <button
                    class="bg-brand-600 hover:bg-brand-700 px-5 py-2 rounded-md font-medium whitespace-nowrap">Subscribe</button>
            </form>
        </div>
    </section>
@endif

<footer class="bg-gray-950 text-gray-300">
    @if (!isset($hideFullFooter))
        <div class="max-w-7xl mx-auto px-4 py-12 grid sm:grid-cols-2 md:grid-cols-5 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    {{-- <span class="w-8 h-8 rounded-lg bg-brand-700 text-white flex items-center justify-center"><i class="fa-solid fa-bag-shopping text-sm"></i></span>
        <span class="text-lg font-semibold text-white">AgriExpress</span> --}}
                    <img src="{{ asset('assets/images/logo-footer.png') }}" title="AgriExpress" alt="AgriExpress"
                        class="img-responsive w-50" />
                </div>
                <p class="text-sm text-gray-400 mb-4">Better products. A brighter you.</p>
                <div class="flex gap-3 text-gray-400">
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-pinterest"></i></a>
                </div>
            </div>
            <div>
                <p class="text-white font-medium mb-3">Quick Links</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('index') }}" class="hover:text-white">Home</a></li>
                    <li><a href="{{ route('shop') }}" class="hover:text-white">Shop</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white">About</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white font-medium mb-3">Customer Service</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">FAQs</a></li>
                    <li><a href="#" class="hover:text-white">Shipping</a></li>
                    <li><a href="#" class="hover:text-white">Returns</a></li>
                    <li><a href="#" class="hover:text-white">Track Order</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white font-medium mb-3">My Account</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('account') }}" class="hover:text-white">Profile</a></li>
                    <li><a href="{{ route('account') }}" class="hover:text-white">Orders</a></li>
                    <li><a href="{{ route('account') }}" class="hover:text-white">Addresses</a></li>
                    <li><a href="{{ route('account') }}" class="hover:text-white">Wishlist</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white font-medium mb-3">We Accept</p>
                <div class="flex flex-wrap gap-2">
                    {{-- <span class="bg-white rounded px-2 py-1 text-blue-700 text-xs font-bold">VISA</span>
        <span class="bg-white rounded px-2 py-1 text-orange-500 text-xs font-bold">MC</span>
        <span class="bg-white rounded px-2 py-1 text-blue-500 text-xs font-bold">PayPal</span>
        <span class="bg-white rounded px-2 py-1 text-gray-800 text-xs font-bold">Pay</span> --}}
                    <img src="{{ asset('assets/images/payments.png') }}" alt="Payment Methods" class="h-6">
                </div>
            </div>
        </div>
    @endif
    <div class="border-t border-gray-800">
        <div
            class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-2">
            <p>&copy; {{ date('Y') }} AgriExpress. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white">Privacy Policy</a>
                <a href="#" class="hover:text-white">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
