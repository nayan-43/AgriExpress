@extends('user.layouts.app')

@section('title', 'Your Cart — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Your Cart</h1>
        <p class="text-gray-500 text-sm mb-6">{{ count($cartItems) }} items</p>

        <div class="grid lg:grid-cols-[1fr_360px] gap-8">
            <div>
                @if (session('status'))
                    <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}</div>
                @endif

                <div class="divide-y divide-gray-100 border border-gray-100 rounded-lg" data-cart-totals
                    data-discount-pct="{{ $discountPct }}" data-tax-pct="{{ $taxPct }}">
                    @foreach ($cartItems as $item)
                        <div class="flex items-center gap-4 p-4" data-cart-row data-price="{{ $item['price'] }}"
                            data-update-url="{{ route('cart.update', $item['id']) }}">
                            <img src="{{ $item['image'] }}" class="w-20 h-20 rounded-md object-cover shrink-0">

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['variant'] }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="font-semibold text-gray-900"
                                            data-line-total>${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                                        @if ($item['old_price'])
                                            <span
                                                class="text-gray-400 line-through text-xs">${{ number_format($item['old_price'], 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center border border-gray-200 rounded-md" data-qty data-min="1"
                                        data-max="10">
                                        <button type="button" data-qty-decrease
                                            class="w-8 h-8 text-gray-600 hover:bg-gray-50">&minus;</button>
                                        <span data-qty-value class="w-8 text-center text-sm">{{ $item['qty'] }}</span>
                                        <button type="button" data-qty-increase
                                            class="w-8 h-8 text-gray-600 hover:bg-gray-50">+</button>
                                    </div>

                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500"><i
                                                class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order summary --}}
            <aside class="border border-gray-100 rounded-lg p-6 h-fit">
                <h2 class="font-semibold text-gray-900 mb-4">Order Summary</h2>
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <div class="flex justify-between"><span>Subtotal</span><span
                            data-summary-subtotal>${{ number_format($subtotal, 2) }}</span></div>
                    @if ($appliedCoupon)
                        <div class="flex justify-between text-emerald-600"><span>Discount
                                ({{ $appliedCoupon }})</span><span
                                data-summary-discount>-${{ number_format($discount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between"><span>Shipping</span><span>Free</span></div>
                    <div class="flex justify-between"><span>Tax ({{ $taxPct }}%)</span><span
                            data-summary-tax>${{ number_format($tax, 2) }}</span></div>
                </div>
                <div class="flex justify-between font-semibold text-gray-900 border-t border-gray-100 pt-4 mb-4">
                    <span>Total</span><span data-summary-total>${{ number_format($total, 2) }}</span>
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <i class="fa-solid fa-tag"></i><span>Have a coupon?</span>
                </div>
                @if ($appliedCoupon)
                    <div
                        class="flex items-center justify-between rounded-md bg-emerald-50 px-3 py-2 mb-5 text-sm text-emerald-700">
                        <span>{{ $appliedCoupon }} applied</span>
                        <form action="{{ route('cart.coupon.remove') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="font-medium hover:underline">Remove</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2 mb-5">
                        @csrf
                        <input type="text" name="code" required placeholder="Enter coupon code"
                            class="flex-1 border border-gray-200 rounded-md px-3 py-2 text-sm outline-none">
                        <button type="submit" class="bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Apply</button>
                    </form>
                @endif
                @error('code')
                    <div class="mb-5 rounded-md px-4 py-3 text-sm text-red-700">
                        {{ $message }}</div>
                @enderror

                <a href="{{ route('checkout') }}"
                    class="block text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium mb-2">Proceed
                    to Checkout</a>
                <a href="{{ route('shop') }}"
                    class="block text-center border border-gray-200 text-gray-700 py-3 rounded-md font-medium hover:bg-gray-50">Continue
                    Shopping</a>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Persist quantity changes from the +/- stepper to the server.
        // app.js already listens for clicks on [data-qty-increase/decrease],
        // updates the on-screen count, and fires a bubbling 'qtychange'
        // event with the new value — this just saves that value.
        document.querySelectorAll('[data-cart-row]').forEach(function(row) {
            let saveTimer = null;

            row.addEventListener('qtychange', function(e) {
                const url = row.dataset.updateUrl;
                if (!url) return;

                clearTimeout(saveTimer);
                saveTimer = setTimeout(function() {
                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            quantity: e.detail.value
                        }),
                    }).catch(function() {
                        // Non-fatal: the visible total is already updated
                        // client-side; a failed save just means a page
                        // refresh would show the previous server value.
                        console.warn('Could not save cart quantity change.');
                    });
                }, 400); // small debounce so rapid +/- clicks send one request
            });
        });
    </script>
@endpush
