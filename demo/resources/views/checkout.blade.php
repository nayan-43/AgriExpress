@extends('layouts.app')

@section('title', 'Checkout — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

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

        <div class="grid lg:grid-cols-[1fr_360px] gap-8">
            <div>
                <h2 class="font-semibold text-gray-900 mb-4">Shipping Address</h2>

                <div class="space-y-4 mb-2" data-swatch-group>
                    @foreach ($addresses as $i => $address)
                        <label
                            class="block border {{ $i === 0 ? 'border-brand-700 bg-brand-50/40' : 'border-gray-200' }} rounded-lg p-4 relative cursor-pointer">
                            <input type="radio" name="shipping_address" value="{{ $address['id'] }}" class="peer sr-only"
                                {{ $i === 0 ? 'checked' : '' }}>
                            <span
                                class="absolute top-4 right-4 w-4 h-4 rounded-full border border-gray-300 peer-checked:bg-brand-700 peer-checked:border-brand-700 flex items-center justify-center">
                                @if ($i === 0)
                                    <i class="fa-solid fa-check text-white text-[8px]"></i>
                                @endif
                            </span>
                            <p class="font-medium text-gray-900 text-sm mb-1">
                                {{ $address['label'] }}
                                @if ($address['default'])
                                    <span class="text-xs text-gray-400 font-normal">(default)</span>
                                @endif
                                <a href="#" class="text-brand-700 text-xs ml-2">Edit</a>
                            </p>
                            <p class="text-sm text-gray-600">{{ $address['name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $address['line1'] }}</p>
                            <p class="text-sm text-gray-600">{{ $address['city'] }}</p>
                            <p class="text-sm text-gray-600">{{ $address['phone'] }}</p>
                        </label>
                    @endforeach
                </div>

                <button class="text-brand-700 text-sm font-medium mb-6 mt-4"><i class="fa-solid fa-plus mr-1"></i> Add New
                    Address</button>
                <br>
                <a href="{{ route('cart') }}" class="text-sm text-gray-500 hover:text-gray-700"><i
                        class="fa-solid fa-arrow-left mr-1"></i> Back to Cart</a>
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
                            <span class="text-sm font-medium">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 text-sm text-gray-600 border-t border-gray-100 pt-4 mb-4">
                    <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600"><span>Discount
                            ({{ $appliedCoupon }})</span><span>-${{ number_format($discount, 2) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>Free</span></div>
                    <div class="flex justify-between"><span>Tax
                            ({{ $taxPct }}%)</span><span>${{ number_format($tax, 2) }}</span></div>
                </div>
                <div class="flex justify-between font-semibold text-gray-900 border-t border-gray-100 pt-4 mb-5">
                    <span>Total</span><span>${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('account') }}"
                    class="block text-center bg-brand-700 hover:bg-brand-800 text-white py-3 rounded-md font-medium">Continue
                    to Shipping Method</a>
            </aside>
        </div>
    </div>
@endsection
