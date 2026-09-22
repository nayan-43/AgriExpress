{{--
  admin/brands/index.blade.php
  Populated by App\Http\Controllers\Admin\BrandController@index.
--}}
@extends('layouts.admin')

@section('title', 'Brands')
@section('active', 'brands')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div><h1 class="text-2xl font-bold">Brands</h1><p class="muted text-sm mt-1">Manage the brands your products belong to</p></div>
    <a href="{{ route('admin.brands.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium flex items-center"><i class="fa-solid fa-plus mr-2"></i>Add brand</a>
</div>

<div class="surface border rounded-2xl scroll-x">
    <table class="w-full text-sm min-w-[640px]">
        <thead><tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
            <th class="px-4 py-3 font-medium">Logo</th><th class="px-4 py-3 font-medium">Name</th>
            <th class="px-4 py-3 font-medium">Slug</th><th class="px-4 py-3 font-medium">Products</th>
            <th class="px-4 py-3 font-medium">Status</th><th class="px-4 py-3 font-medium">Action</th>
        </tr></thead>
        <tbody class="divide-b">
            @forelse ($brands as $b)
                <tr>
                    <td class="px-4 py-3"><x-thumb :src="$b->logo_url" icon="fa-copyright" size="w-10 h-10" /></td>
                    <td class="px-4 py-3 font-medium">{{ $b->name }}</td>
                    <td class="px-4 py-3 muted">{{ $b->slug }}</td>
                    <td class="px-4 py-3">{{ $b->products_count }}</td>
                    <td class="px-4 py-3"><x-pill :status="$b->status ? 'Active' : 'Inactive'" /></td>
                    <td class="px-4 py-3">
                        <x-action-buttons
                            :edit-route="route('admin.brands.edit', $b)"
                            :delete-route="route('admin.brands.destroy', $b)" />
                    </td>
                </tr>
            @empty
                <x-empty-state :colspan="6" message="No brands yet." />
            @endforelse
        </tbody>
    </table>
    <x-pagination-bar :paginator="$brands" />
</div>
@endsection
