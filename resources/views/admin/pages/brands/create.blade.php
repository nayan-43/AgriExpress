{{--
  admin/brands/create.blade.php
  Doubles as the edit form.
--}}
@extends('admin.layouts.admin')

@section('title', $brand->exists ? 'Edit brand' : 'Add brand')
@section('active', 'brands')

@section('content')
<form action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if ($brand->exists) @method('PUT') @endif

    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">{{ $brand->exists ? 'Edit brand' : 'Add brand' }}</h1>
            <p class="muted text-sm mt-1">Brands appear in the product form's Brand dropdown and on storefront filters</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.brands.index') }}" class="surface border rounded-lg px-4 h-10 text-sm flex items-center">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium">Save brand</button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-[1fr_300px] gap-5">
        <div class="surface border rounded-2xl p-5 space-y-4 text-sm h-fit">
            <div>
                <label for="name" class="block mb-1.5 font-medium">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" required value="{{ old('name', $brand->name) }}" class="w-full border bd rounded-lg px-3 h-10">
            </div>
            <div>
                <label for="slug" class="block mb-1.5 font-medium">Slug</label>
                <input id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="auto-generate-from-name">
            </div>
            <div>
                <label for="description" class="block mb-1.5 font-medium">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full border bd rounded-lg px-3 py-2">{{ old('description', $brand->description) }}</textarea>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="status" value="1" class="w-4 h-4 rounded accent-blue-600" @checked(old('status', $brand->status ?? true))>
                Active
            </label>

            <div class="pt-4 border-t bd space-y-3">
                <p class="font-medium">SEO</p>
                <div>
                    <label for="meta_title" class="block mb-1.5 font-medium">Meta title</label>
                    <input id="meta_title" name="meta_title" value="{{ old('meta_title', $brand->meta_title) }}" class="w-full border bd rounded-lg px-3 h-10">
                </div>
                <div>
                    <label for="meta_description" class="block mb-1.5 font-medium">Meta description</label>
                    <textarea id="meta_description" name="meta_description" rows="2" class="w-full border bd rounded-lg px-3 py-2">{{ old('meta_description', $brand->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="surface border rounded-2xl p-5 h-fit">
            <h2 class="font-semibold mb-3">Logo</h2>
            <label for="logo" class="block relative border-2 border-dashed rounded-xl text-center cursor-pointer overflow-hidden" style="border-color:#c7d6f5;background:var(--bg)">
                <div id="logoPreviewWrap" class="{{ $brand->logo_url ? '' : 'hidden' }} p-4">
                    <img id="logoPreview" src="{{ $brand->logo_url }}" class="w-full h-28 object-contain">
                </div>
                <div id="logoPlaceholder" class="py-8 {{ $brand->logo_url ? 'hidden' : '' }}">
                    <i class="fa-solid fa-copyright text-xl text-blue-500"></i>
                    <p class="text-[13px] mt-2 font-medium">Click to upload</p>
                    <p class="text-[11px] muted mt-0.5">Transparent PNG works best</p>
                </div>
                <input type="file" id="logo" name="logo" accept="image/*" class="hidden">
            </label>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const logoInput = document.getElementById('logo');
const logoPreview = document.getElementById('logoPreview');
const logoPreviewWrap = document.getElementById('logoPreviewWrap');
const logoPlaceholder = document.getElementById('logoPlaceholder');

logoInput.addEventListener('change', () => {
    const file = logoInput.files[0];
    if (!file) return;
    logoPreview.src = URL.createObjectURL(file);
    logoPreviewWrap.classList.remove('hidden');
    logoPlaceholder.classList.add('hidden');
});
</script>
@endpush
