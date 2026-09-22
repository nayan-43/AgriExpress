{{--
  admin/settings/index.blade.php
  Controller: return view('admin.settings.index', ['store' => $store, 'tab' => $tab])
  where $store is the store's settings row/model and $tab is the active
  settings section (defaults to 'general').
--}}
@extends('admin.layouts.admin')

@section('title', 'Settings')
@section('active', 'settings')

@php
    $store =
        $store ??
        (object) [
            'name' => 'AgriExpress',
            'phone' => '+91 98765 43210',
            'timezone' => '(GMT+05:30) India Standard Time',
            'currency' => 'INR — Indian Rupee',
            'weight_unit' => 'Kilogram (kg)',
            'address' => '123 Green Park, Kolkata, West Bengal 700001',
        ];
    $tab = $tab ?? 'general';
    $tabs = [
        'general' => ['label' => 'General settings', 'icon' => 'fa-gear'],
        'admin_email' => ['label' => 'Admin email', 'icon' => 'fa-envelope'],
        'admin_password' => ['label' => 'Admin password', 'icon' => 'fa-key'],
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
            <h2 class="font-semibold mb-5">{{ $tabs[$tab]['label'] ?? 'General settings' }}</h2>

            @if ($tab === 'general')
                <form action="{{ route('admin.settings.update', ['tab' => 'general']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="general">
                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label for="store_name" class="block mb-1.5 font-medium">Store name</label>
                            <input id="store_name" name="name" value="{{ old('name', $store->name) }}"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div>
                            <label for="store_phone" class="block mb-1.5 font-medium">Phone number</label>
                            <input id="store_phone" name="phone" value="{{ old('phone', $store->phone) }}"
                                class="w-full border bd rounded-lg px-3 h-10">
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
            @elseif ($tab === 'admin_email')
                <form action="{{ route('admin.settings.update', ['tab' => 'admin_email']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="admin_email">
                    <div class="max-w-xl space-y-4 text-sm">
                        <div>
                            <label for="admin_email" class="block mb-1.5 font-medium">Admin email</label>
                            <input id="admin_email" name="email" type="email"
                                value="{{ old('email', $admin->email ?? '') }}"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-5 h-10 text-sm font-medium">Update
                                email</button>
                        </div>
                    </div>
                </form>
            @elseif ($tab === 'admin_password')
                <form action="{{ route('admin.settings.update', ['tab' => 'admin_password']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="admin_password">
                    <div class="max-w-xl space-y-4 text-sm">
                        <div>
                            <label for="current_password" class="block mb-1.5 font-medium">Current password</label>
                            <input id="current_password" type="password" name="current_password"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div>
                            <label for="password" class="block mb-1.5 font-medium">New password</label>
                            <input id="password" type="password" name="password"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block mb-1.5 font-medium">Confirm new password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="w-full border bd rounded-lg px-3 h-10">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-5 h-10 text-sm font-medium">Update
                                password</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
