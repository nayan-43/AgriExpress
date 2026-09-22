@extends('user.layouts.app')

@section('title', 'Addresses — AgriExpress')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center gap-3 mb-6"><a href="{{ route('account') }}" class="text-gray-400 hover:text-gray-700"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <h1 class="text-2xl font-bold text-gray-900">My Addresses</h1>
        </div>
        @if (session('status'))
            <div class="mb-5 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif
        <div class="grid lg:grid-cols-[1fr_360px] gap-8">
            <div class="space-y-3">
                @forelse($addresses as $address)
                    <div class="border border-gray-100 rounded-lg p-5 flex justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold text-gray-900 mb-1">{{ ucfirst($address->type) }} @if ($address->is_default)
                                    <span class="text-xs text-emerald-600">Default</span>
                                @endif
                            </p>
                            <p>{{ trim($address->first_name . ' ' . $address->last_name) }}</p>
                            <p>{{ $address->address_line_1 }}</p>
                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            <p>{{ $address->phone }}</p>
                        </div>
                        <form action="{{ route('account.addresses.destroy', $address) }}" method="POST">@csrf
                            @method('DELETE')<button class="text-gray-400 hover:text-red-500" title="Remove address"><i
                                    class="fa-regular fa-trash-can"></i></button></form>
                    </div>
                @empty
                    <div class="border border-dashed border-gray-200 rounded-lg p-10 text-center text-sm text-gray-500">No
                        saved addresses yet.</div>
                @endforelse
            </div>
            <form action="{{ route('account.addresses.store') }}" method="POST"
                class="border border-gray-100 rounded-lg p-5 space-y-3">
                @csrf
                <h2 class="font-semibold text-gray-900">Add address</h2>
                <select name="type" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm">
                    <option value="home">Home</option>
                    <option value="office">Office</option>
                </select>
                <div class="grid grid-cols-2 gap-2"><input name="first_name" required placeholder="First name"
                        class="border border-gray-200 rounded-md px-3 py-2 text-sm"><input name="last_name"
                        placeholder="Last name" class="border border-gray-200 rounded-md px-3 py-2 text-sm"></div>
                <input name="phone" required placeholder="Phone"
                    class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm"><input name="address_line_1" required
                    placeholder="Address" class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm"><input
                    name="address_line_2" placeholder="Apartment, suite"
                    class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm">
                <div class="grid grid-cols-2 gap-2"><input name="city" required placeholder="City"
                        class="border border-gray-200 rounded-md px-3 py-2 text-sm"><input name="state" required
                        placeholder="State" class="border border-gray-200 rounded-md px-3 py-2 text-sm"><input
                        name="postal_code" required placeholder="Postal code"
                        class="border border-gray-200 rounded-md px-3 py-2 text-sm"><input name="country" required
                        value="India" class="border border-gray-200 rounded-md px-3 py-2 text-sm"></div>
                <label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" name="is_default"
                        value="1"> Make default</label>
                <button class="w-full bg-brand-700 hover:bg-brand-800 text-white py-2.5 rounded-md text-sm font-medium">Save
                    address</button>
            </form>
        </div>
    </div>
@endsection
