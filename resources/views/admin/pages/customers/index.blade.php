{{--
  admin/customers/index.blade.php
  Populated by App\Http\Controllers\Admin\CustomerController@index.
--}}
@extends('admin.layouts.admin')

@section('title', 'Customers')
@section('active', 'customers')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div><h1 class="text-2xl font-bold">Customers</h1><p class="muted text-sm mt-1">Manage your customers</p></div>
    <button class="surface border rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-file-export mr-2 muted"></i>Export</button>
</div>

<div class="surface border rounded-2xl">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="p-4 flex flex-wrap gap-3 border-b bd">
        <label class="flex items-center gap-2 flex-1 min-w-[220px] border bd rounded-lg px-3 h-10">
            <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="flex-1 text-sm border-0" placeholder="Search by name, email, phone...">
        </label>
        <select name="status" class="border bd rounded-lg px-3 h-10 text-sm">
            <option value="">All customers</option>
            <option value="Active" @selected(request('status') === 'Active')>Active</option>
            <option value="Blocked" @selected(request('status') === 'Blocked')>Blocked</option>
        </select>
        <button type="submit" class="border bd rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-filter mr-2 muted"></i>Filter</button>
    </form>

    <div class="scroll-x">
        <table class="w-full text-sm min-w-[880px]">
            <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                <th class="px-5 py-3 font-medium">Name</th><th class="px-5 py-3 font-medium">Email</th>
                <th class="px-5 py-3 font-medium">Phone</th><th class="px-5 py-3 font-medium">Orders</th>
                <th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Action</th>
            </tr></thead>
            <tbody class="divide-b">
                @forelse ($customers as $i => $c)
                    <tr>
                        <td class="px-5 py-3.5"><span class="flex items-center gap-2.5"><x-avatar :name="$c->name" :index="$i" /><span class="font-medium">{{ $c->name }}</span></span></td>
                        <td class="px-5 py-3.5 muted">{{ $c->email }}</td>
                        <td class="px-5 py-3.5 muted">{{ $c->phone ?? '—' }}</td>
                        <td class="px-5 py-3.5">{{ $c->orders_count }}</td>
                        <td class="px-5 py-3.5"><x-pill :status="$c->status ? 'Active' : 'Blocked'" /></td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 text-[13px]">
                                <a href="{{ route('admin.customers.show', $c) }}" class="w-8 h-8 rounded-lg border bd grid place-items-center text-blue-600" aria-label="View"><i class="fa-regular fa-eye"></i></a>
                                <form action="{{ route('admin.customers.updateStatus', $c) }}" method="POST" onsubmit="return confirm('{{ $c->status ? 'Block' : 'Unblock' }} {{ $c->name }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $c->status ? 0 : 1 }}">
                                    <button type="submit" class="w-8 h-8 rounded-lg border bd grid place-items-center {{ $c->status ? 'text-red-500' : 'text-emerald-600' }}" aria-label="{{ $c->status ? 'Block' : 'Unblock' }}">
                                        <i class="fa-solid {{ $c->status ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state :colspan="6" message="No customers match this search." />
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination-bar :paginator="$customers" />
</div>
@endsection
