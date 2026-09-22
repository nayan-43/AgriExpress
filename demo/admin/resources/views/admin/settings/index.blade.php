{{--
  admin/settings/index.blade.php
  Controller: return view('admin.settings.index', ['store' => $store, 'tab' => $tab])
  where $store is the store's settings row/model and $tab is the active
  settings section (defaults to 'general').
--}}
@extends('layouts.admin')

@section('title', 'Settings')
@section('active', 'settings')

@php
    $store =
        $store ??
        (object) [
            'name' => 'AgriExpress',
            'email' => 'support@shopease.com',
            'phone' => '+91 98765 43210',
            'timezone' => '(GMT+05:30) India Standard Time',
            'currency' => 'INR — Indian Rupee',
            'weight_unit' => 'Kilogram (kg)',
            'address' => '123 Green Park, Kolkata, West Bengal 700001',
        ];
    $tab = $tab ?? 'general';
    $tabs = [
        'general' => ['label' => 'General', 'icon' => 'fa-gear'],
        'store' => ['label' => 'Store information', 'icon' => 'fa-store'],
        'shipping' => ['label' => 'Shipping', 'icon' => 'fa-truck'],
        'payment' => ['label' => 'Payment', 'icon' => 'fa-credit-card'],
        'email' => ['label' => 'Email', 'icon' => 'fa-envelope'],
        'seo' => ['label' => 'SEO', 'icon' => 'fa-magnifying-glass-chart'],
        'maintenance' => ['label' => 'Maintenance', 'icon' => 'fa-screwdriver-wrench'],
    ];
@endphp

@section('content')
    <div class="mb-5">
        <h1 class="text-2xl font-bold">Settings</h1>
        <p class="muted text-sm mt-1">Manage your store settings</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[230px_1fr] gap-5">
        <nav class="surface border rounded-2xl p-2 h-fit text-sm">
            @foreach ($tabs as $key => $t)
                <a href="{{ route('admin.settings.index', ['tab' => $key]) }}"
                    class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg {{ $tab === $key ? 'bg-blue-600 text-white' : 'hover:bg-black/5' }}">
                    <i class="fa-solid {{ $t['icon'] }} w-4 text-[13px]"></i>{{ $t['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="surface border rounded-2xl p-5">
            <h2 class="font-semibold mb-5">{{ $tabs[$tab]['label'] ?? 'General' }}</h2>

            @if ($tab === 'general')
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label for="store_name" class="block mb-1.5 font-medium">Store name</label>
                            <input id="store_name" name="name" value="{{ old('name', $store->name) }}"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div>
                            <label for="store_email" class="block mb-1.5 font-medium">Store email</label>
                            <input id="store_email" name="email" type="email" value="{{ old('email', $store->email) }}"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div>
                            <label for="store_phone" class="block mb-1.5 font-medium">Phone number</label>
                            <input id="store_phone" name="phone" value="{{ old('phone', $store->phone) }}"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div>
                            <label for="timezone" class="block mb-1.5 font-medium">Timezone</label>
                            <select id="timezone" name="timezone" class="w-full border bd rounded-lg px-3 h-10">
                                <option @selected($store->timezone === '(GMT+05:30) India Standard Time')>(GMT+05:30) India Standard Time</option>
                                <option @selected($store->timezone === '(GMT+00:00) UTC')>(GMT+00:00) UTC</option>
                            </select>
                        </div>
                        <div>
                            <label for="currency" class="block mb-1.5 font-medium">Currency</label>
                            <select id="currency" name="currency" class="w-full border bd rounded-lg px-3 h-10">
                                <option @selected($store->currency === 'INR — Indian Rupee')>INR &mdash; Indian Rupee</option>
                                <option @selected($store->currency === 'USD — US Dollar')>USD &mdash; US Dollar</option>
                            </select>
                        </div>
                        <div>
                            <label for="weight_unit" class="block mb-1.5 font-medium">Weight unit</label>
                            <select id="weight_unit" name="weight_unit" class="w-full border bd rounded-lg px-3 h-10">
                                <option @selected($store->weight_unit === 'Kilogram (kg)')>Kilogram (kg)</option>
                                <option @selected($store->weight_unit === 'Gram (g)')>Gram (g)</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="address" class="block mb-1.5 font-medium">Store address</label>
                            <textarea id="address" name="address" rows="3" class="w-full border bd rounded-lg px-3 py-2">{{ old('address', $store->address) }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-5 h-10 text-sm font-medium">Save
                            changes</button>
                    </div>
                </form>
            @else
                <p class="muted text-sm">The {{ strtolower($tabs[$tab]['label']) }} settings form goes here &mdash; follow
                    the same
                    pattern as the General tab: a form posting to its own route, with fields for this section.</p>
            @endif
        </div>
    </div>
@endsection
