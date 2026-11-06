@extends('admin.layouts.admin')

@section('title', 'Brands')
@section('active', 'brands')

@section('style')
    <style>
        .brands {
            width: 20px;

            & img {
                width: 100%;
                height: auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">Brands</h1>
            <p class="muted text-sm mt-1">Manage the brands your products belong to</p>
        </div>
        <a href="{{ route('admin.brands.create') }}"
            class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium flex items-center"><i
                class="fa-solid fa-plus mr-2"></i>Add brand</a>
    </div>

    <div class="surface border rounded-2xl scroll-x">
        <form id="brands-bulk-form" method="POST" action="{{ route('admin.brands.bulk-action') }}" data-bulk-form
            data-confirm-message="Apply this action to the selected brands?">
            @csrf
            <div class="bulk-actions flex flex-wrap items-center gap-3 px-4 py-3 border-b bd">
                <span class="muted text-sm"><span data-selected-count>0</span> selected</span>
                <select name="action" required class="border bd rounded-lg px-3 h-9 text-sm">
                    <option value="">Bulk action</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="submit" data-bulk-submit disabled
                    class="bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg px-3 h-9 text-sm font-medium">
                    Apply
                </button>
            </div>
        </form>
        <table class="w-full text-sm min-w-160">
            <thead>
                <tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                    <th class="px-4 py-3"><input type="checkbox" onclick="toggleAll(this,'b-check')"></th>
                    <th class="px-4 py-3 font-medium">Logo</th>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Slug</th>
                    <th class="px-4 py-3 font-medium">Products</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-b">
                @forelse ($brands as $b)
                    <tr>
                        <td class="px-4 py-3"><input form="brands-bulk-form" type="checkbox" name="selected[]" class="b-check" value="{{ $b->id }}"></td>
                        <td class="px-4 py-3 brands"><x-thumb :src="$b->logo_url" icon="fa-copyright" size="w-10 h-10" /></td>
                        <td class="px-4 py-3 font-medium">{{ $b->name }}</td>
                        <td class="px-4 py-3 muted">{{ $b->slug }}</td>
                        <td class="px-4 py-3">{{ $b->products_count }}</td>
                        <td class="px-4 py-3"><x-pill :status="$b->status ? 'Active' : 'Inactive'" /></td>
                        <td class="px-4 py-3">
                            <x-action-buttons :edit-route="route('admin.brands.edit', $b)" :delete-route="route('admin.brands.destroy', $b)" />
                        </td>
                    </tr>
                @empty
                    <x-empty-state :colspan="7" message="No brands yet." />
                @endforelse
            </tbody>
        </table>
        <x-pagination-bar :paginator="$brands" />
    </div>
@endsection
