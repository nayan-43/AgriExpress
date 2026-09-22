{{--
  admin/coupons/index.blade.php
  Controller: App\Http\Controllers\Admin\CouponController@index
--}}
@extends('admin.layouts.admin')

@section('title', 'Coupons')
@section('active', 'coupons')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div><h1 class="text-2xl font-bold">Coupons</h1><p class="muted text-sm mt-1">Manage discount coupons</p></div>
    <a href="{{ route('admin.coupons.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium flex items-center"><i class="fa-solid fa-plus mr-2"></i>Add coupon</a>
</div>

<div class="surface border rounded-2xl">
    <form method="GET" action="{{ route('admin.coupons.index') }}" class="p-4 flex flex-wrap gap-3 border-b bd">
        <label class="flex items-center gap-2 flex-1 min-w-[220px] border bd rounded-lg px-3 h-10">
            <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="flex-1 text-sm border-0" placeholder="Search coupon code...">
        </label>
        <select name="status" onchange="this.form.submit()" class="border bd rounded-lg px-3 h-10 text-sm">
            <option value="">All status</option>
            <option value="Active" @selected(request('status') === 'Active')>Active</option>
            <option value="Inactive" @selected(request('status') === 'Inactive')>Inactive</option>
        </select>
        <button type="submit" class="border bd rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-filter mr-2 muted"></i>Filter</button>
    </form>

    <div class="scroll-x">
        <table class="w-full text-sm min-w-[900px]">
            <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                <th class="px-5 py-3 font-medium">Code</th><th class="px-5 py-3 font-medium">Discount</th>
                <th class="px-5 py-3 font-medium">Type</th><th class="px-5 py-3 font-medium">Min order</th>
                <th class="px-5 py-3 font-medium">Usage</th><th class="px-5 py-3 font-medium">Expiry date</th>
                <th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Action</th>
            </tr></thead>
            <tbody class="divide-b">
                @forelse ($coupons as $c)
                    <tr>
                        <td class="px-5 py-3.5 font-semibold">{{ $c->code }}</td>
                        <td class="px-5 py-3.5">{{ $c->discount_label }}</td>
                        <td class="px-5 py-3.5 muted capitalize">{{ $c->type }}</td>
                        <td class="px-5 py-3.5">${{ number_format($c->minimum_order_amount, 2) }}</td>
                        <td class="px-5 py-3.5 muted">
                            {{ $c->usages_count ?? $c->usages()->count() }}{{ $c->usage_limit ? ' / '.$c->usage_limit : '' }}
                        </td>
                        <td class="px-5 py-3.5 muted">{{ $c->expires_at?->format('M d, Y') ?? 'No expiry' }}</td>
                        <td class="px-5 py-3.5">
                            <x-pill :status="$c->is_expired ? 'Inactive' : ($c->status ? 'Active' : 'Inactive')" />
                        </td>
                        <td class="px-5 py-3.5">
                            <x-action-buttons
                                :edit-route="route('admin.coupons.edit', $c)"
                                :delete-route="route('admin.coupons.destroy', $c)" />
                        </td>
                    </tr>
                @empty
                    <x-empty-state :colspan="8" message="No coupons found. Create one to get started." />
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination-bar :paginator="$coupons" />
</div>
@endsection
