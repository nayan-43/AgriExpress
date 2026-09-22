@extends('user.layouts.app')

@section('title', 'Checkout — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>
        @php
            $checkoutInput =
                'w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700 focus:ring-1 focus:ring-brand-700/20';
        @endphp

        <div class="flex items-center justify-center gap-4 mb-10 text-sm">
            <div class="flex items-center gap-2 text-brand-700 font-medium">
                <span class="w-7 h-7 rounded-full bg-brand-700 text-white flex items-center justify-center text-xs">1</span>
                Shipping Address
            </div>
            <div class="w-12 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-gray-400">
                <span class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-xs">2</span>
                Shipping Method
            </div>
            <div class="w-12 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-gray-400">
                <span class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-xs">3</span>
                Payment
            </div>
        </div>

        <form action="{{ route('checkout.stripe') }}" method="POST">
            @csrf
            <div class="grid lg:grid-cols-[1fr_360px] gap-8">
                <div>
                    <div class="border border-gray-100 rounded-lg p-5 sm:p-6">
                        <h2 class="font-semibold text-gray-900 mb-1">Shipping Address</h2>
                        <p class="text-sm text-gray-500 mb-5">Where should we deliver your order?</p>

                        <div class="space-y-4 mb-2" data-swatch-group>
                            @foreach ($addresses as $i => $address)
                                <label
                                    class="block border {{ $i === 0 ? 'border-brand-700 bg-brand-50/40' : 'border-gray-200' }} rounded-lg p-4 relative cursor-pointer">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="peer sr-only"
                                        {{ $i === 0 ? 'checked' : '' }}>
                                    <span
                                        class="absolute top-4 right-4 w-4 h-4 rounded-full border border-gray-300 peer-checked:bg-brand-700 peer-checked:border-brand-700 flex items-center justify-center">
                                        @if ($i === 0)
                                            <i class="fa-solid fa-check text-white text-[8px]"></i>
                                        @endif
                                    </span>
                                    <p class="font-medium text-gray-900 text-sm mb-1">
                                        {{ ucfirst($address->type) }}
                                        @if ($address->is_default)
                                            <span class="text-xs text-gray-400 font-normal">(default)</span>
                                        @endif
                                        <a href="#" class="text-brand-700 text-xs ml-2">Edit</a>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ trim($address->first_name . ' ' . $address->last_name) }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $address->address_line_1 }}</p>
                                    <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->state }}
                                        {{ $address->postal_code }}</p>
                                    <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                </label>
                            @endforeach
                            @if ($addresses->isEmpty())
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <label class="text-sm font-medium text-gray-700">First name<input name="first_name"
                                            required class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700">Last name<input name="last_name"
                                            class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700">Phone<input name="phone" required
                                            class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700 sm:col-span-2">Address<input
                                            name="address_line_1" required class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700 sm:col-span-2">Apartment, suite <span
                                            class="font-normal text-gray-400">(optional)</span><input name="address_line_2"
                                            class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700">City<input name="city" required
                                            class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700">State<input name="state" required
                                            class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700">Postal code<input name="postal_code"
                                            required class="{{ $checkoutInput }} mt-1"></label>
                                    <label class="text-sm font-medium text-gray-700">Country<input name="country" required
                                            value="India" class="{{ $checkoutInput }} mt-1"></label>
                                </div>
                            @endif
                        </div>

                        <button class="text-brand-700 text-sm font-medium mb-6 mt-4"><i class="fa-solid fa-plus mr-1"></i>
                            Add
                            New Address</button>
                        <div class="border border-gray-100 rounded-lg p-5 sm:p-6 border-t-4 border-t-brand-700 mt-6">
                            <h2 class="font-semibold text-gray-900 mb-1">Billing Address</h2>
                            <p class="text-sm text-gray-500 mb-4">Use a different billing address if needed.</p>
                            <label class="flex items-center gap-2 text-sm text-gray-600 mb-5">
                                <input type="checkbox" name="same_as_billing" value="1" checked data-same-billing
                                    class="accent-brand-700">
                                Billing address is the same as shipping
                            </label>
                            <div data-billing-fields style="display: none" class="grid sm:grid-cols-2 gap-4">
                                <label class="text-sm font-medium text-gray-700">First name<input name="billing_first_name"
                                        disabled class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700">Last name<input name="billing_last_name"
                                        disabled class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700">Phone<input name="billing_phone" disabled
                                        class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700 sm:col-span-2">Address<input
                                        name="billing_address_line_1" disabled class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700 sm:col-span-2">Apartment, suite <span
                                        class="font-normal text-gray-400">(optional)</span><input
                                        name="billing_address_line_2" disabled class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700">City<input name="billing_city" disabled
                                        class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700">State<input name="billing_state" disabled
                                        class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700">Postal code<input
                                        name="billing_postal_code" disabled class="{{ $checkoutInput }} mt-1"></label>
                                <label class="text-sm font-medium text-gray-700">Country<input name="billing_country"
                                        value="India" disabled class="{{ $checkoutInput }} mt-1"></label>
                            </div>
                        </div>
                        <br>
                        <a href="{{ route('cart') }}" class="text-sm text-gray-500 hover:text-gray-700"><i
                                class="fa-solid fa-arrow-left mr-1"></i> Back to Cart</a>
                    </div>
                </div>

                <aside class="border border-gray-100 rounded-lg p-6 h-fit">
                    <h2 class="font-semibold text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-3 mb-4">
                        @foreach ($cartItems as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item['image'] }}" class="w-12 h-12 rounded-md object-cover">
                                <div class="flex-1 text-sm">
                                    <p class="text-gray-800">{{ $item['name'] }}</p>
                                    <p class="text-gray-400 text-xs">x{{ $item['qty'] }}</p>
                                </div>
                                <span
                                    class="text-sm font-medium">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-2 text-sm text-gray-600 border-t border-gray-100 pt-4 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if ($appliedCoupon)
                            <div class="flex justify-between text-emerald-600"><span>Discount
                                    ({{ $appliedCoupon }})</span><span>-${{ number_format($discount, 2) }}</span></div>
                        @endif
                        <div class="flex justify-between"><span>Shipping</span><span>Free</span></div>
                        <div class="flex justify-between"><span>Tax
                                ({{ $taxPct }}%)</span><span>${{ number_format($tax, 2) }}</span></div>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-900 border-t border-gray-100 pt-4 mb-5">
                        <span>Total</span><span>${{ number_format($total, 2) }}</span>
                    </div>
                    <h2 class="font-semibold text-gray-900 mb-3">Payment Method</h2>
                    <div class="space-y-2 mb-5">
                        <label class="flex items-center gap-3 border border-gray-200 rounded-md p-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="stripe" checked class="accent-brand-700">
                            <span><span class="block text-sm font-medium text-gray-800">Card / Stripe</span><span
                                    class="block text-xs text-gray-500">Pay securely online</span></span>
                        </label>
                        <label class="flex items-center gap-3 border border-gray-200 rounded-md p-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" class="accent-brand-700">
                            <span><span class="block text-sm font-medium text-gray-800">Cash on Delivery</span><span
                                    class="block text-xs text-gray-500">Pay when your order arrives</span></span>
                        </label>
                    </div>
                    <button type="submit"
                        class="block w-full text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium">Place
                        Order</button>
                </aside>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelector('[data-same-billing]')?.addEventListener('change', function(event) {
            const fields = document.querySelector('[data-billing-fields]');
            if (!fields) return;
            fields.style.display = event.target.checked ? 'none' : 'grid';
            fields.querySelectorAll('input').forEach(function(input) {
                input.disabled = event.target.checked;
                input.required = !event.target.checked && input.name !== 'billing_last_name' && input
                    .name !== 'billing_address_line_2';
            });
        });
    </script>
@endpush
