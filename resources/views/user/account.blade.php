@extends('user.layouts.app')

@section('title', 'My Account — AgriExpress')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 grid lg:grid-cols-[240px_1fr] gap-8">

        <aside class="border border-gray-100 rounded-lg p-4 h-fit">
            <div class="flex items-center gap-3 px-2 pb-4 mb-4 border-b border-gray-100">
                <span
                    class="w-10 h-10 rounded-full bg-brand-700 text-white flex items-center justify-center font-semibold">{{ $user['initials'] }}</span>
                <div>
                    <p class="font-medium text-gray-900 text-sm">{{ $user['name'] }}</p>
                    <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                </div>
            </div>
            <nav class="space-y-1 text-sm">
                <a href="{{ route('account') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-md bg-brand-50 text-brand-700 font-medium"><i
                        class="fa-solid fa-gauge w-4"></i> Dashboard</a>
                <a href="#orders" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50"><i
                        class="fa-solid fa-box w-4"></i> My Orders</a>
                <a href="{{ route('account.addresses') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50"><i
                        class="fa-solid fa-location-dot w-4"></i> Addresses</a>
                <a href="{{ route('wishlist') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50"><i
                        class="fa-regular fa-heart w-4"></i> Wishlist</a>
                <a href="{{ route('account.profile') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50"><i
                        class="fa-solid fa-gear w-4"></i> Profile Settings</a>
                <a href="{{ route('account.profile') }}#password"
                    class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50"><i
                        class="fa-solid fa-key w-4"></i> Change Password</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 text-left"><i
                            class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout</button>
                </form>
            </nav>
        </aside>

        <div>
            @if (session('status'))
                <div class="mb-5 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700"
                    role="status">
                    {{ session('status') }}
                </div>
            @endif
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Account</h1>
                    <p class="text-gray-500 text-sm">Manage your account and orders</p>
                </div>
                <button
                    class="border border-gray-200 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"><i
                        class="fa-solid fa-pen mr-1"></i> Edit Profile</button>
            </div>

            <div class="flex items-center gap-4 border border-gray-100 rounded-lg p-5 mb-6">
                <span
                    class="w-14 h-14 rounded-full bg-brand-700 text-white flex items-center justify-center text-xl font-semibold">{{ $user['initials'] }}</span>
                <div>
                    <p class="font-semibold text-gray-900">{{ $user['name'] }} <i
                            class="fa-solid fa-circle-check text-emerald-500 text-sm ml-1"></i></p>
                    <p class="text-sm text-gray-500">{{ $user['email'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="border border-gray-100 rounded-lg p-5 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['orders'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Orders</p>
                </div>
                <div class="border border-gray-100 rounded-lg p-5 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['wishlist'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Wishlist Items</p>
                </div>
                <div class="border border-gray-100 rounded-lg p-5 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['addresses'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Addresses</p>
                </div>
            </div>

            <div id="orders" class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">Recent Orders</h2>
                <a href="#orders" class="text-brand-700 text-sm font-medium hover:underline">View All <i
                        class="fa-solid fa-arrow-right text-xs"></i></a>
            </div>

            <div class="border border-gray-100 rounded-lg overflow-hidden overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium">Order #</th>
                            <th class="text-left px-4 py-3 font-medium">Date</th>
                            <th class="text-left px-4 py-3 font-medium">Status</th>
                            <th class="text-left px-4 py-3 font-medium">Total</th>
                            <th class="text-left px-4 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-800">#{{ $order['number'] }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $order['date'] }}</td>
                                <td class="px-4 py-3"><span
                                        class="{{ $order['status_classes'] }} text-xs font-medium px-2 py-1 rounded-full">{{ $order['status'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-800">${{ number_format($order['total'], 2) }}</td>
                                <td class="px-4 py-3"><a href="{{ route('order.details', $order['number']) }}"
                                        class="border border-gray-200 rounded-md px-3 py-1 text-xs hover:bg-gray-50">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
