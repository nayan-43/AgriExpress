{{--
  admin/coupons/create.blade.php
  Doubles as the edit form — App\Http\Controllers\Admin\CouponController
  passes an empty Coupon() for create and a loaded one for edit.
--}}
@extends('admin.layouts.admin')

@section('title', $coupon->exists ? 'Edit coupon' : 'Add coupon')
@section('active', 'coupons')

@section('content')
<form id="coupon-form" data-custom-validation action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
      method="POST" novalidate>
    @csrf
    @if ($coupon->exists) @method('PUT') @endif

    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">{{ $coupon->exists ? 'Edit coupon' : 'Add coupon' }}</h1>
            <p class="muted text-sm mt-1">Set up a discount code customers can apply at checkout</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.coupons.index') }}" class="surface border rounded-lg px-4 h-10 text-sm flex items-center">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium">Save coupon</button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="surface border rounded-2xl p-5 space-y-4 text-sm">
            <h2 class="font-semibold">Coupon details</h2>
            <div>
                <label for="code" class="block mb-1.5 font-medium">Code <span class="text-red-500">*</span></label>
                <input id="code" name="code" required maxlength="50"
                       value="{{ old('code', $coupon->code) }}"
                       class="w-full border bd rounded-lg px-3 h-10 uppercase" placeholder="e.g. WELCOME10">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block mb-1.5 font-medium">Discount type <span class="text-red-500">*</span></label>
                    <select id="type" name="type" required class="w-full border bd rounded-lg px-3 h-10">
                        <option value="percentage" @selected(old('type', $coupon->type) === 'percentage')>Percentage</option>
                        <option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Fixed amount</option>
                    </select>
                </div>
                <div>
                    <label for="value" class="block mb-1.5 font-medium">Value <span class="text-red-500">*</span></label>
                    <input id="value" name="value" type="number" step="0.01" required
                           value="{{ old('value', $coupon->value) }}" class="w-full border bd rounded-lg px-3 h-10">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="minimum_order_amount" class="block mb-1.5 font-medium">Minimum order</label>
                    <input id="minimum_order_amount" name="minimum_order_amount" type="number" step="0.01"
                           value="{{ old('minimum_order_amount', $coupon->minimum_order_amount) }}" class="w-full border bd rounded-lg px-3 h-10">
                </div>
                <div>
                    <label for="maximum_discount" class="block mb-1.5 font-medium">Max discount cap</label>
                    <input id="maximum_discount" name="maximum_discount" type="number" step="0.01"
                           value="{{ old('maximum_discount', $coupon->maximum_discount) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="Optional — for percentage coupons">
                </div>
            </div>

            <label class="flex items-center gap-2 cursor-pointer pt-2">
                <input type="checkbox" name="status" value="1" class="w-4 h-4 rounded accent-blue-600" @checked(old('status', $coupon->status ?? true))>
                Active
            </label>
        </div>

        <div class="surface border rounded-2xl p-5 space-y-4 text-sm h-fit">
            <h2 class="font-semibold">Limits &amp; validity</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="usage_limit" class="block mb-1.5 font-medium">Total usage limit</label>
                    <input id="usage_limit" name="usage_limit" type="number" min="1"
                           value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="Unlimited">
                </div>
                <div>
                    <label for="usage_limit_per_user" class="block mb-1.5 font-medium">Per customer</label>
                    <input id="usage_limit_per_user" name="usage_limit_per_user" type="number" min="1"
                           value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="Unlimited">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="starts_at" class="block mb-1.5 font-medium">Starts</label>
                    <input id="starts_at" name="starts_at" type="date"
                           @unless ($coupon->exists) min="{{ now()->toDateString() }}" @endunless
                           value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}" class="w-full border bd rounded-lg px-3 h-10">
                    <p id="starts_at_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div>
                    <label for="expires_at" class="block mb-1.5 font-medium">Expires</label>
                    <input id="expires_at" name="expires_at" type="date"
                           min="{{ now()->toDateString() }}"
                           value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}" class="w-full border bd rounded-lg px-3 h-10">
                    <p id="expires_at_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
            </div>
            <p class="text-[12px] muted">Leave dates blank for a coupon that runs indefinitely.</p>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('coupon-form');
            const starts = document.getElementById('starts_at');
            const expires = document.getElementById('expires_at');
            const startsError = document.getElementById('starts_at_error');
            const expiresError = document.getElementById('expires_at_error');
            const today = @json(now()->toDateString());
            const isEdit = @json($coupon->exists);

            if (!form || !starts || !expires) return;

            const nextDay = (date) => {
                const next = new Date(`${date}T00:00:00`);
                next.setDate(next.getDate() + 1);
                return next.toISOString().slice(0, 10);
            };

            const showError = (input, element, message) => {
                input.setCustomValidity(message);
                element.textContent = message;
                element.classList.toggle('hidden', !message);
                input.classList.toggle('border-red-500', Boolean(message));
            };

            const validateDates = () => {
                showError(starts, startsError, !isEdit && starts.value && starts.value < today
                    ? 'Start date must be today or later.'
                    : '');

                expires.min = starts.value && starts.value >= today ? nextDay(starts.value) : today;

                let expiresMessage = '';
                if (expires.value && expires.value < today) {
                    expiresMessage = 'Expiry date must be today or later.';
                } else if (starts.value && expires.value && expires.value <= starts.value) {
                    expiresMessage = 'Expiry date must be after the start date.';
                }
                showError(expires, expiresError, expiresMessage);
            };

            starts.addEventListener('change', validateDates);
            expires.addEventListener('change', validateDates);
            form.addEventListener('submit', (event) => {
                validateDates();
                if (!form.checkValidity()) {
                    event.preventDefault();
                    form.querySelector(':invalid')?.focus();
                }
            });
            validateDates();
        })();
    </script>
@endpush
