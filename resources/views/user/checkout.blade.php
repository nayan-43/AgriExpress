@extends('user.layouts.app')

@section('title', 'Checkout — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>
        @php
            $checkoutInput =
                'w-full border border-gray-200 rounded-md px-4 py-2.5 text-sm outline-none focus:border-brand-700 focus:ring-1 focus:ring-brand-700/20';
        @endphp

        {{-- Status bar: reflects the three sections below, in order, and
             tracks which one is in view as the customer scrolls. --}}
        <div class="flex items-center justify-center gap-4 mb-10 text-sm" data-checkout-steps>
            <div class="flex items-center gap-2 text-gray-400" data-step="billing">
                <span data-step-badge class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-xs">1</span>
                <span data-step-label>Billing Address</span>
            </div>
            <div class="w-12 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-gray-400" data-step="shipping">
                <span data-step-badge class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-xs">2</span>
                <span data-step-label>Shipping Address</span>
            </div>
            <div class="w-12 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-gray-400" data-step="payment">
                <span data-step-badge class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-xs">3</span>
                <span data-step-label>Payment</span>
            </div>
        </div>

        <form action="{{ route('checkout.stripe') }}" method="POST">
            @csrf
            <div class="grid lg:grid-cols-[1fr_360px] gap-8">
                <div class="space-y-6">

                    {{-- ============ 1. BILLING ============ --}}
                    <div id="billing-section" data-checkout-section="billing"
                         class="border border-gray-100 rounded-lg p-5 sm:p-6 border-t-4 border-t-brand-700 scroll-mt-28">
                        <h2 class="font-semibold text-gray-900 mb-1"><span class="text-brand-700">1.</span> Billing Address</h2>
                        <p class="text-sm text-gray-500 mb-5">This is the address tied to your payment method.</p>

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

                        <button type="button" class="text-brand-700 text-sm font-medium mt-4"><i class="fa-solid fa-plus mr-1"></i>
                            Add New Address</button>
                    </div>

                    {{-- ============ 2. SHIPPING ============ --}}
                    <div id="shipping-section" data-checkout-section="shipping"
                         class="border border-gray-100 rounded-lg p-5 sm:p-6 border-t-4 border-t-brand-700 scroll-mt-28">
                        <h2 class="font-semibold text-gray-900 mb-1"><span class="text-brand-700">2.</span> Shipping Address</h2>
                        <p class="text-sm text-gray-500 mb-4">Where should we deliver your order?</p>
                        <label class="flex items-center gap-2 text-sm text-gray-600 mb-5">
                            <input type="checkbox" name="same_as_billing" value="1" checked data-same-billing
                                class="accent-brand-700">
                            Shipping address is the same as billing
                        </label>
                        <div data-shipping-fields style="display: none" class="grid sm:grid-cols-2 gap-4">
                            <label class="text-sm font-medium text-gray-700">First name<input name="shipping_first_name"
                                    disabled class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700">Last name<input name="shipping_last_name"
                                    disabled class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700">Phone<input name="shipping_phone" disabled
                                    class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700 sm:col-span-2">Address<input
                                    name="shipping_address_line_1" disabled class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700 sm:col-span-2">Apartment, suite <span
                                    class="font-normal text-gray-400">(optional)</span><input
                                    name="shipping_address_line_2" disabled class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700">City<input name="shipping_city" disabled
                                    class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700">State<input name="shipping_state" disabled
                                    class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700">Postal code<input
                                    name="shipping_postal_code" disabled class="{{ $checkoutInput }} mt-1"></label>
                            <label class="text-sm font-medium text-gray-700">Country<input name="shipping_country"
                                    value="India" disabled class="{{ $checkoutInput }} mt-1"></label>
                        </div>
                    </div>

                    {{-- ============ 3. PAYMENT ============ --}}
                    <div id="payment-section" data-checkout-section="payment"
                         class="border border-gray-100 rounded-lg p-5 sm:p-6 border-t-4 border-t-brand-700 scroll-mt-28">
                        <h2 class="font-semibold text-gray-900 mb-1"><span class="text-brand-700">3.</span> Payment</h2>
                        <p class="text-sm text-gray-500 mb-5">Choose how you'd like to pay.</p>
                        <div class="space-y-2">
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
                    </div>

                    <a href="{{ route('cart') }}" class="text-sm text-gray-500 hover:text-gray-700 inline-block"><i
                            class="fa-solid fa-arrow-left mr-1"></i> Back to Cart</a>
                </div>

                <aside class="border border-gray-100 rounded-lg p-6 h-fit lg:sticky lg:top-24">
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
        // Same-as-billing toggle for the shipping section.
        document.querySelector('[data-same-billing]')?.addEventListener('change', function(event) {
            const fields = document.querySelector('[data-shipping-fields]');
            if (!fields) return;
            fields.style.display = event.target.checked ? 'none' : 'grid';
            fields.querySelectorAll('input').forEach(function(input) {
                input.disabled = event.target.checked;
                input.required = !event.target.checked && input.name !== 'shipping_last_name' &&
                    input.name !== 'shipping_address_line_2';
            });
        });

        // Status bar: highlight whichever of the three sections is
        // currently in view as the customer scrolls the form.
        (function () {
            const sections = Array.from(document.querySelectorAll('[data-checkout-section]'));
            const steps = document.querySelectorAll('[data-checkout-steps] [data-step]');
            if (!sections.length || !steps.length) return;

            function activate(name) {
                steps.forEach(function (step) {
                    const isActive = step.dataset.step === name;
                    const badge = step.querySelector('[data-step-badge]');
                    step.classList.toggle('text-brand-700', isActive);
                    step.classList.toggle('font-medium', isActive);
                    step.classList.toggle('text-gray-400', !isActive);
                    badge.classList.toggle('bg-brand-700', isActive);
                    badge.classList.toggle('text-white', isActive);
                    badge.classList.toggle('border-gray-300', !isActive);
                    badge.classList.toggle('border-brand-700', isActive);
                });
            }

            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) activate(entry.target.dataset.checkoutSection);
                });
            }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

            sections.forEach(function (section) { observer.observe(section); });
            activate('billing');
        })();
    </script>
@endpush
