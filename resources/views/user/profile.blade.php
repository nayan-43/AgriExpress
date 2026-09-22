@extends('user.layouts.app')

@section('title', 'Profile Settings — AgriExpress')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('account') }}" class="text-gray-400 hover:text-gray-700"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
        </div>

        @if (session('status'))
            <div class="mb-5 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <form action="{{ route('account.profile.update') }}" method="POST"
            class="border border-gray-100 rounded-lg p-6 space-y-4">
            @csrf @method('PUT')
            <h2 class="font-semibold text-gray-900">Account details</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="text-sm text-gray-700 outline-none focus:border-brand-700">Name<input name="name"
                        value="{{ old('name', $account->name) }}" required
                        class="mt-1 w-full border border-gray-200 rounded-md px-3 py-2 outline-none focus:border-brand-700"></label>
                <label class="text-sm text-gray-700 outline-none focus:border-brand-700">Phone<input name="phone"
                        value="{{ old('phone', $account->phone) }}"
                        class="mt-1 w-full border border-gray-200 rounded-md px-3 py-2 outline-none focus:border-brand-700"></label>
            </div>
            <label class="block text-sm text-gray-700">Email<input type="email" name="email"
                    value="{{ old('email', $account->email) }}" readonly
                    class="mt-1 w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed outline-none focus:border-brand-700"></label>
            <button class="bg-brand-700 hover:bg-brand-800 text-white px-5 py-2.5 rounded-md text-sm font-medium">Save
                details</button>
        </form>

        <form id="password" action="{{ route('account.profile.update') }}" method="POST"
            class="border border-gray-100 rounded-lg p-6 space-y-4 mt-6">
            @csrf @method('PUT')
            <input type="hidden" name="name" value="{{ $account->name }}"><input type="hidden" name="email"
                value="{{ $account->email }}"><input type="hidden" name="phone" value="{{ $account->phone }}">
            <h2 class="font-semibold text-gray-900">Change password</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="text-sm text-gray-700 outline-none focus:border-brand-700">New password<input type="password"
                        name="password" required
                        class="mt-1 w-full border border-gray-200 rounded-md px-3 py-2 outline-none focus:border-brand-700"></label>
                <label class="text-sm text-gray-700 outline-none focus:border-brand-700">Confirm password<input
                        type="password" name="password_confirmation" required
                        class="mt-1 w-full border border-gray-200 rounded-md px-3 py-2 outline-none focus:border-brand-700"></label>
            </div>
            <button class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 rounded-md text-sm font-medium">Update
                password</button>
        </form>
    </div>
@endsection
