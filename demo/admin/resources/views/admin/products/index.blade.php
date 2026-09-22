{{--
  admin/products/index.blade.php
  Populated by App\Http\Controllers\Admin\ProductController@index.
--}}
@extends('layouts.admin')

@section('title', 'Products')
@section('active', 'products')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div><h1 class="text-2xl font-bold">Products</h1><p class="muted text-sm mt-1">Manage your store products</p></div>
    <div class="flex gap-2">
        <button class="surface border rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-file-import mr-2 muted"></i>Import</button>
        <button class="surface border rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-file-export mr-2 muted"></i>Export</button>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium flex items-center"><i class="fa-solid fa-plus mr-2"></i>Add product</a>
    </div>
</div>

<div class="surface border rounded-2xl">
    <form method="GET" action="{{ route('admin.products.index') }}" class="p-4 flex flex-wrap gap-3 border-b bd">
        <label class="flex items-center gap-2 flex-1 min-w-[220px] border bd rounded-lg px-3 h-10">
            <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="flex-1 text-sm border-0" placeholder="Search by name, SKU...">
        </label>
        <select name="category" class="border bd rounded-lg px-3 h-10 text-sm">
            <option value="">All categories</option>
            @foreach ($categories as $c)
                <option value="{{ $c->slug }}" @selected(request('category') === $c->slug)>{{ $c->name }}</option>
            @endforeach
        </select>
        <select name="status" class="border bd rounded-lg px-3 h-10 text-sm">
            <option value="">All status</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
        </select>
        <button type="submit" class="border bd rounded-lg px-4 h-10 text-sm"><i class="fa-solid fa-filter mr-2 muted"></i>Filter</button>
    </form>

    <div class="scroll-x">
        <table class="w-full text-sm min-w-[860px]">
            <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                <th class="px-4 py-3"><input type="checkbox" onclick="toggleAll(this,'p-check')"></th>
                <th class="px-4 py-3 font-medium">Image</th><th class="px-4 py-3 font-medium">Name</th>
                <th class="px-4 py-3 font-medium">Category</th><th class="px-4 py-3 font-medium">Price</th>
                <th class="px-4 py-3 font-medium">Stock</th><th class="px-4 py-3 font-medium">Status</th>
                <th class="px-4 py-3 font-medium">Action</th>
            </tr></thead>
            <tbody class="divide-b">
                @forelse ($products as $p)
                    <tr>
                        <td class="px-4 py-3"><input type="checkbox" class="p-check" value="{{ $p->id }}"></td>
                        <td class="px-4 py-3"><x-thumb :src="$p->main_image_url" icon="fa-box" size="w-10 h-10" /></td>
                        <td class="px-4 py-3 font-medium">{{ $p->name }}<span class="block muted text-[11.5px] font-normal">{{ $p->sku }}</span></td>
                        <td class="px-4 py-3 text-blue-600">{{ $p->category->name ?? '—' }}</td>
                        <td class="px-4 py-3 font-medium">
                            ${{ number_format($p->sale_price ?? $p->price, 2) }}
                            @if($p->sale_price)
                                <span class="block muted text-[11.5px] line-through font-normal">${{ number_format($p->price, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 muted">{{ $p->stock }}</td>
                        <td class="px-4 py-3"><x-pill :status="$p->status ? 'Published' : 'Draft'" /></td>
                        <td class="px-4 py-3">
                            <x-action-buttons
                                :edit-route="route('admin.products.edit', $p)"
                                :delete-route="route('admin.products.destroy', $p)" />
                        </td>
                    </tr>
                @empty
                    <x-empty-state :colspan="8" message="No products match this search. Try a different name or clear the filters." />
                @endforelse
            </tbody>
        </table>
    </div>

    <x-pagination-bar :paginator="$products" />
</div>
@endsection
