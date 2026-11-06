@extends('admin.layouts.admin')

@section('title', 'Banners')
@section('active', 'banners')

@section('content')
    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">Banners</h1>
            <p class="muted text-sm mt-1">Manage the homepage hero carousel</p>
        </div>
        <a href="{{ route('admin.banners.create') }}"
            class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Add banner
        </a>
    </div>

    <div class="surface border rounded-2xl scroll-x">
        <table class="w-full text-sm min-w-215">
            <thead>
                <tr class="text-left muted text-[12.5px]" style="background:var(--bg)">
                    <th class="px-4 py-3 font-medium">Preview</th>
                    <th class="px-4 py-3 font-medium">Content</th>
                    <th class="px-4 py-3 font-medium">CTA</th>
                    <th class="px-4 py-3 font-medium">Order</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-b">
                @forelse ($banners as $banner)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                class="w-36 h-20 rounded-lg object-cover">
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $banner->title }}</p>
                            <p class="muted text-xs mt-1">{{ $banner->eyebrow ?: 'No eyebrow' }}</p>
                        </td>
                        <td class="px-4 py-3 muted">{{ $banner->cta_label ?: 'No button' }}</td>
                        <td class="px-4 py-3">{{ $banner->sort_order }}</td>
                        <td class="px-4 py-3"><x-pill :status="$banner->status ? 'Active' : 'Inactive'" /></td>
                        <td class="px-4 py-3">
                            <x-action-buttons :edit-route="route('admin.banners.edit', $banner)"
                                :delete-route="route('admin.banners.destroy', $banner)" />
                        </td>
                    </tr>
                @empty
                    <x-empty-state :colspan="6" message="No banners yet. Add one to manage the homepage hero." />
                @endforelse
            </tbody>
        </table>
        <x-pagination-bar :paginator="$banners" />
    </div>
@endsection
