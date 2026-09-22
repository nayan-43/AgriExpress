{{--
  admin/categories/create.blade.php
  Doubles as the edit form. Two image fields — a square "image" (used in
  menus/cards) and a wide "banner" (used on the category landing page) —
  each with its own live preview.
--}}
@extends('layouts.admin')

@section('title', $category->exists ? 'Edit category' : 'Add category')
@section('active', 'categories')

@section('content')
<form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if ($category->exists) @method('PUT') @endif

    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">{{ $category->exists ? 'Edit category' : 'Add category' }}</h1>
            <p class="muted text-sm mt-1">Organize your products into browsable categories</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="surface border rounded-lg px-4 h-10 text-sm flex items-center">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium">Save category</button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-5">
        <div class="surface border rounded-2xl p-5 space-y-4 text-sm h-fit">
            <div>
                <label for="name" class="block mb-1.5 font-medium">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" required value="{{ old('name', $category->name) }}" class="w-full border bd rounded-lg px-3 h-10">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="slug" class="block mb-1.5 font-medium">Slug</label>
                    <input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="auto-generate-from-name">
                </div>
                <div>
                    <label for="parent_id" class="block mb-1.5 font-medium">Parent category</label>
                    <select id="parent_id" name="parent_id" class="w-full border bd rounded-lg px-3 h-10">
                        <option value="">None (top-level)</option>
                        @foreach ($parents as $p)
                            <option value="{{ $p->id }}" @selected(old('parent_id', $category->parent_id) == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="menu_name" class="block mb-1.5 font-medium">Menu display name</label>
                <input id="menu_name" name="menu_name" value="{{ old('menu_name', $category->menu_name) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="Shown in the storefront nav, if different from Name">
            </div>
            <div>
                <label for="description" class="block mb-1.5 font-medium">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full border bd rounded-lg px-3 py-2">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block mb-1.5 font-medium">Sort order</label>
                    <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="w-full border bd rounded-lg px-3 h-10">
                </div>
                <label class="flex items-center gap-2 self-end pb-2 cursor-pointer">
                    <input type="checkbox" name="status" value="1" class="w-4 h-4 rounded accent-blue-600" @checked(old('status', $category->status ?? true))>
                    Active
                </label>
            </div>

            <div class="pt-4 border-t bd">
                <p class="font-medium mb-3">SEO</p>
                <div class="space-y-3">
                    <div>
                        <label for="meta_title" class="block mb-1.5 font-medium">Meta title</label>
                        <input id="meta_title" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}" class="w-full border bd rounded-lg px-3 h-10">
                    </div>
                    <div>
                        <label for="meta_description" class="block mb-1.5 font-medium">Meta description</label>
                        <textarea id="meta_description" name="meta_description" rows="2" class="w-full border bd rounded-lg px-3 py-2">{{ old('meta_description', $category->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-3">Category image</h2>
                <label for="image" class="block relative border-2 border-dashed rounded-xl text-center cursor-pointer overflow-hidden" style="border-color:#c7d6f5;background:var(--bg)">
                    <div id="imagePreviewWrap" class="{{ $category->image_url ? '' : 'hidden' }}">
                        <img id="imagePreview" src="{{ $category->image_url }}" class="w-full h-32 object-cover">
                    </div>
                    <div id="imagePlaceholder" class="py-7 {{ $category->image_url ? 'hidden' : '' }}">
                        <i class="fa-solid fa-image text-xl text-blue-500"></i>
                        <p class="text-[13px] mt-2 font-medium">Click to upload</p>
                        <p class="text-[11px] muted mt-0.5">Square image works best</p>
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden">
                </label>
            </div>

            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-3">Banner image</h2>
                <label for="banner" class="block relative border-2 border-dashed rounded-xl text-center cursor-pointer overflow-hidden" style="border-color:#c7d6f5;background:var(--bg)">
                    <div id="bannerPreviewWrap" class="{{ $category->banner_url ? '' : 'hidden' }}">
                        <img id="bannerPreview" src="{{ $category->banner_url }}" class="w-full h-24 object-cover">
                    </div>
                    <div id="bannerPlaceholder" class="py-7 {{ $category->banner_url ? 'hidden' : '' }}">
                        <i class="fa-solid fa-panorama text-xl text-blue-500"></i>
                        <p class="text-[13px] mt-2 font-medium">Click to upload</p>
                        <p class="text-[11px] muted mt-0.5">Wide image for the category page header</p>
                    </div>
                    <input type="file" id="banner" name="banner" accept="image/*" class="hidden">
                </label>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Same pattern for both single-image fields: swap the placeholder for a
// live preview the moment a file is chosen.
function wireImagePreview(inputId, previewId, wrapId, placeholderId){
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const wrap = document.getElementById(wrapId);
    const placeholder = document.getElementById(placeholderId);

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        wrap.classList.remove('hidden');
        placeholder.classList.add('hidden');
    });
}

wireImagePreview('image', 'imagePreview', 'imagePreviewWrap', 'imagePlaceholder');
wireImagePreview('banner', 'bannerPreview', 'bannerPreviewWrap', 'bannerPlaceholder');
</script>
@endpush
