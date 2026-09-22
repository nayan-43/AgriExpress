@extends('user.layouts.app')

@section('title', 'Order #' . $order['number'] . ' — AgriExpress')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8 flex-wrap gap-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order Details <span
                        class="text-gray-400 font-normal">#{{ $order['number'] }}</span></h1>
                <p class="text-gray-500 text-sm">Placed on {{ $order['placed_at'] }} <span class="mx-1">&bull;</span>
                    Delivered on {{ $order['delivered_at'] }}</p>
            </div>
        </div>

        {{-- Tracker --}}
        <div class="flex items-center justify-between mb-10 relative">
            <div class="absolute top-4 left-0 right-0 h-0.5 bg-brand-700 z-0"></div>
            @foreach ($order['timeline'] as $step)
                <div class="flex flex-col items-center gap-2 relative z-10 bg-white px-2">
                    <span class="w-8 h-8 rounded-full bg-brand-700 text-white flex items-center justify-center"><i
                            class="fa-solid {{ $step['icon'] }} text-xs"></i></span>
                    <p class="text-xs font-medium text-gray-800">{{ $step['label'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $step['date'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-[1fr_320px] gap-8">
            <div>
                <h2 class="font-semibold text-gray-900 mb-4">Order Items</h2>
                <div class="divide-y divide-gray-100 border border-gray-100 rounded-lg">
                    @foreach ($order['items'] as $item)
                        <div class="flex items-center gap-4 p-4">
                            <img src="{{ $item['image'] }}" class="w-14 h-14 rounded-md object-cover">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['variant'] }}</p>
                            </div>
                            <div class="text-right text-sm">
                                <p class="font-medium text-gray-900">${{ number_format($item['price'], 2) }}</p>
                                <p class="text-xs text-gray-400">x{{ $item['qty'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-6">
                <div class="border border-gray-100 rounded-lg p-5">
                    <h3 class="font-semibold text-gray-900 mb-2 text-sm">Shipping Address</h3>
                    <p class="text-sm text-gray-600">{{ $order['address']['name'] }}</p>
                    <p class="text-sm text-gray-600">{{ $order['address']['line1'] }}</p>
                    <p class="text-sm text-gray-600">{{ $order['address']['city'] }}</p>
                    <p class="text-sm text-gray-600">{{ $order['address']['phone'] }}</p>
                </div>
                <div class="border border-gray-100 rounded-lg p-5">
                    <h3 class="font-semibold text-gray-900 mb-2 text-sm">Payment Method</h3>
                    <p class="text-sm text-gray-600"><i class="fa-solid fa-credit-card mr-1 text-gray-400"></i>
                        {{ $order['payment_method'] }}</p>
                    <p class="text-xs text-emerald-600 mt-1"><i class="fa-solid fa-circle-check"></i>
                        {{ $order['payment_status'] }}</p>
                </div>
                <div class="flex gap-3">
                    <button
                        class="flex-1 border border-gray-200 rounded-md py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Track
                        Package</button>
                    <form action="{{ route('cart.reorder', $order['number']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-brand-700 hover:bg-brand-800 text-white rounded-md py-2.5 text-sm font-medium">Reorder</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
@endsection
