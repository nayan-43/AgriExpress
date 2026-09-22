@extends('admin.layouts.admin')

@section('title', 'Categories')
@section('active', 'categories')

@section('content')
    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">Categories</h1>
            <p class="muted text-sm mt-1">Manage product categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
            class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium flex items-center"><i
                class="fa-solid fa-plus mr-2"></i>Add category</a>
    </div>

    <div class="surface border rounded-2xl scroll-x">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="p-4 flex flex-wrap gap-3 border-b bd"
            data-admin-filter data-admin-target="categories-results">
            <label class="flex items-center gap-2 flex-1 min-w-55 border bd rounded-lg px-3 h-10">
                <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" class="flex-1 text-sm border-0"
                    placeholder="Search by name or slug...">
            </label>
            <select name="status" class="border bd rounded-lg px-3 h-10 text-sm">
                <option value="">All status</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <select name="sort" class="border bd rounded-lg px-3 h-10 text-sm" aria-label="Sort categories">
                <option value="">Display order</option>
                <option value="name_desc" @selected(request('sort') === 'name_desc')>Name Z-A</option>
                <option value="products" @selected(request('sort') === 'products')>Most products</option>
            </select>
        </form>
        <div id="categories-results">
            <table class="w-full text-sm min-w-190">
                <thead>
                    <tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                        <th class="px-4 py-3"><input type="checkbox" onclick="toggleAll(this,'c-check')"></th>
                        <th class="px-4 py-3 font-medium">Image</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Parent</th>
                        <th class="px-4 py-3 font-medium">Slug</th>
                        <th class="px-4 py-3 font-medium">Products</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-b">
                    @forelse ($categories as $c)
                        <tr>
                            <td class="px-4 py-3"><input type="checkbox" class="c-check" value="{{ $c->id }}"></td>
                            <td class="px-4 py-3"><x-thumb :src="$c->image_url" icon="fa-layer-group" size="w-10 h-10" /></td>
                            <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                            <td class="px-4 py-3 muted">{{ $c->parent->name ?? '—' }}</td>
                            <td class="px-4 py-3 muted">{{ $c->slug }}</td>
                            <td class="px-4 py-3">{{ $c->products_count }}</td>
                            <td class="px-4 py-3"><x-pill :status="$c->status ? 'Active' : 'Inactive'" /></td>
                            <td class="px-4 py-3">
                                <x-action-buttons :edit-route="route('admin.categories.edit', $c)" :delete-route="route('admin.categories.destroy', $c)" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state :colspan="8"
                            message="No categories yet. Create one to start organizing products." />
                    @endforelse
                </tbody>
            </table>
            <x-pagination-bar :paginator="$categories" />
        </div>
    </div>
@endsection
