{{--
  admin/products/create.blade.php
  Doubles as the edit form — App\Http\Controllers\Admin\ProductController
  passes an empty `product` for create and a loaded one (with `images`) for
  edit. Images upload straight to Supabase Storage in the controller; this
  view only needs to preview them client-side before submit.
--}}
@extends('layouts.admin')

@section('title', $product->exists ? 'Edit product' : 'Add product')
@section('active', 'products')

@section('content')
<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    @if ($product->exists) @method('PUT') @endif

    <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
        <div>
            <h1 class="text-2xl font-bold">{{ $product->exists ? 'Edit product' : 'Add product' }}</h1>
            <p class="muted text-sm mt-1">{{ $product->exists ? 'Update this product\'s details' : 'Create a new product for your store' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="surface border rounded-lg px-4 h-10 text-sm flex items-center">Cancel</a>
            <button type="submit" name="status" value="0" class="surface border rounded-lg px-4 h-10 text-sm">Save draft</button>
            <button type="submit" name="status" value="1" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium"><i class="fa-solid fa-paper-plane mr-2"></i>Publish</button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">
            <p class="font-medium mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-5">
        <div class="space-y-5">
            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-4">Basic information</h2>
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-1.5 font-medium">Product name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" required value="{{ old('name', $product->name) }}"
                               class="w-full border bd rounded-lg px-3 h-10" placeholder="Enter product name">
                    </div>
                    <div>
                        <label for="slug" class="block mb-1.5 font-medium">Slug</label>
                        <input id="slug" name="slug" value="{{ old('slug', $product->slug) }}"
                               class="w-full border bd rounded-lg px-3 h-10" placeholder="auto-generate-from-name">
                    </div>
                    <div>
                        <label for="sku" class="block mb-1.5 font-medium">SKU <span class="text-red-500">*</span></label>
                        <input id="sku" name="sku" required value="{{ old('sku', $product->sku) }}"
                               class="w-full border bd rounded-lg px-3 h-10" placeholder="e.g. SKU-001">
                    </div>
                    <div>
                        <label for="category_id" class="block mb-1.5 font-medium">Category <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required class="w-full border bd rounded-lg px-3 h-10">
                            <option value="">Select category</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="brand_id" class="block mb-1.5 font-medium">Brand</label>
                        <select id="brand_id" name="brand_id" class="w-full border bd rounded-lg px-3 h-10">
                            <option value="">Select brand</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id) == $b->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="short_description" class="block mb-1.5 font-medium">Short description</label>
                        <textarea id="short_description" name="short_description" rows="2"
                                  class="w-full border bd rounded-lg px-3 py-2"
                                  placeholder="Shown on listing cards...">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-3">Full description</h2>
                <div class="border bd rounded-lg">
                    <div class="flex flex-wrap gap-1 p-2 border-b bd">
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-bold text-xs"></i></button>
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-italic text-xs"></i></button>
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-underline text-xs"></i></button>
                        <span class="w-px h-6 self-center mx-1" style="background:var(--border)"></span>
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-list-ul text-xs"></i></button>
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-list-ol text-xs"></i></button>
                        <span class="w-px h-6 self-center mx-1" style="background:var(--border)"></span>
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-align-left text-xs"></i></button>
                        <button type="button" class="w-8 h-8 rounded hover:bg-black/5"><i class="fa-solid fa-link text-xs"></i></button>
                    </div>
                    <div id="descriptionEditor" contenteditable class="min-h-[160px] p-3 text-sm">{!! old('description', $product->description) !!}</div>
                    <textarea name="description" id="descriptionInput" class="hidden"></textarea>
                </div>
            </div>

            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-4">SEO</h2>
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="sm:col-span-2">
                        <label for="meta_title" class="block mb-1.5 font-medium">Meta title</label>
                        <input id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full border bd rounded-lg px-3 h-10">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="meta_description" class="block mb-1.5 font-medium">Meta description</label>
                        <textarea id="meta_description" name="meta_description" rows="2" class="w-full border bd rounded-lg px-3 py-2">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="meta_keywords" class="block mb-1.5 font-medium">Meta keywords</label>
                        <input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="w-full border bd rounded-lg px-3 h-10" placeholder="comma, separated, keywords">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            {{-- ===== Cover image — single, with preview ===== --}}
            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-4">Cover image</h2>
                <label for="main_image" class="block relative border-2 border-dashed rounded-xl text-center cursor-pointer overflow-hidden"
                       style="border-color:#c7d6f5;background:var(--bg)">
                    <div id="mainImagePreviewWrap" class="{{ $product->main_image_url ? '' : 'hidden' }}">
                        <img id="mainImagePreview" src="{{ $product->main_image_url }}" alt="" class="w-full h-44 object-cover">
                    </div>
                    <div id="mainImagePlaceholder" class="py-8 {{ $product->main_image_url ? 'hidden' : '' }}">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-blue-500"></i>
                        <p class="text-sm mt-2 font-medium">Click to upload or drag and drop</p>
                        <p class="text-[11.5px] muted mt-1">JPG, PNG, WEBP &mdash; max 2 MB</p>
                    </div>
                    <input type="file" id="main_image" name="main_image" accept="image/*" class="hidden">
                </label>
                <p class="text-[11.5px] muted mt-2">This is the image shown in product listings.</p>
            </div>

            {{-- ===== Gallery — multiple, with preview grid ===== --}}
            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-1">Gallery images</h2>
                <p class="text-[11.5px] muted mb-3">Extra photos shown on the product page (angles, packaging, in use...).</p>

                <label for="gallery" class="block border-2 border-dashed rounded-xl py-6 text-center cursor-pointer" style="border-color:#c7d6f5;background:var(--bg)">
                    <i class="fa-solid fa-images text-xl text-blue-500"></i>
                    <p class="text-sm mt-2 font-medium">Add gallery images</p>
                    <p class="text-[11.5px] muted mt-1">You can select multiple files</p>
                    <input type="file" id="gallery" accept="image/*" multiple class="hidden">
                </label>

                {{-- Existing images (edit mode): check "Remove" to delete on save --}}
                @if ($product->exists && $product->images->isNotEmpty())
                    <p class="text-[11.5px] font-medium mt-4 mb-2">Existing images</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($product->images as $image)
                            <label class="relative block rounded-lg overflow-hidden border bd group">
                                <img src="{{ $image->image_url }}" class="w-full h-20 object-cover">
                                <span class="absolute inset-0 bg-black/50 opacity-0 group-has-[:checked]:opacity-100 flex items-center justify-center transition-opacity">
                                    <i class="fa-solid fa-trash text-white text-sm"></i>
                                </span>
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="absolute top-1 right-1 w-4 h-4">
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] muted mt-1.5">Check an image to remove it when you save.</p>
                @endif

                {{-- New gallery previews, built entirely in JS below --}}
                <div id="galleryPreview" class="grid grid-cols-3 gap-2 mt-4"></div>
            </div>

            <div class="surface border rounded-2xl p-5">
                <h2 class="font-semibold mb-4">Price &amp; inventory</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <label for="price" class="block mb-1.5 font-medium">Regular price <span class="text-red-500">*</span></label>
                        <input id="price" name="price" type="number" step="0.01" required
                               value="{{ old('price', $product->price) }}" class="w-full border bd rounded-lg px-3 h-10">
                    </div>
                    <div>
                        <label for="sale_price" class="block mb-1.5 font-medium">Sale price</label>
                        <input id="sale_price" name="sale_price" type="number" step="0.01"
                               value="{{ old('sale_price', $product->sale_price) }}" class="w-full border bd rounded-lg px-3 h-10">
                    </div>
                    <div class="col-span-2">
                        <label for="stock" class="block mb-1.5 font-medium">Stock quantity <span class="text-red-500">*</span></label>
                        <input id="stock" name="stock" type="number" required
                               value="{{ old('stock', $product->stock ?? 0) }}" class="w-full border bd rounded-lg px-3 h-10">
                    </div>
                </div>
                <label class="flex items-center gap-2 mt-4 text-sm cursor-pointer">
                    <input type="checkbox" name="featured" value="1" class="w-4 h-4 rounded accent-blue-600" @checked(old('featured', $product->featured))>
                    Feature this product on the homepage
                </label>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// ---- Full description: mirror the contenteditable block into the hidden textarea that submits with the form.
const editor = document.getElementById('descriptionEditor');
const descInput = document.getElementById('descriptionInput');
function syncDescription(){ descInput.value = editor.innerHTML; }
editor.addEventListener('input', syncDescription);
syncDescription();

// ---- Cover image preview: swap the dropzone placeholder for the chosen file.
const mainImageInput = document.getElementById('main_image');
const mainImagePreview = document.getElementById('mainImagePreview');
const mainImagePreviewWrap = document.getElementById('mainImagePreviewWrap');
const mainImagePlaceholder = document.getElementById('mainImagePlaceholder');

mainImageInput.addEventListener('change', () => {
    const file = mainImageInput.files[0];
    if (!file) return;
    mainImagePreview.src = URL.createObjectURL(file);
    mainImagePreviewWrap.classList.remove('hidden');
    mainImagePlaceholder.classList.add('hidden');
});

// ---- Gallery preview: build a thumbnail grid for every newly chosen file,
// backed by a DataTransfer object so individual files can be removed
// before submit (plain <input type="file"> FileLists are read-only).
const galleryInput = document.getElementById('gallery');
const galleryPreview = document.getElementById('galleryPreview');
let galleryFiles = [];

galleryInput.addEventListener('change', () => {
    galleryFiles = galleryFiles.concat(Array.from(galleryInput.files));
    renderGalleryPreview();
});

function renderGalleryPreview(){
    galleryPreview.innerHTML = '';
    galleryFiles.forEach((file, index) => {
        const url = URL.createObjectURL(file);
        const wrap = document.createElement('div');
        wrap.className = 'relative rounded-lg overflow-hidden border bd';
        wrap.innerHTML = `
            <img src="${url}" class="w-full h-20 object-cover">
            <button type="button" data-index="${index}"
                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-black/60 text-white text-[10px] grid place-items-center">
                <i class="fa-solid fa-xmark"></i>
            </button>`;
        wrap.querySelector('button').addEventListener('click', () => {
            galleryFiles.splice(index, 1);
            renderGalleryPreview();
        });
        galleryPreview.appendChild(wrap);
    });
    syncGalleryInput();
}

function syncGalleryInput(){
    const dt = new DataTransfer();
    galleryFiles.forEach(file => dt.items.add(file));
    galleryInput.files = dt.files;
}

// The visible #gallery input is what the user clicks/drops into, but its
// name attribute intentionally isn't "gallery[]" — we submit the synced
// files under the real field name via this hidden clone instead, so
// removed previews are never sent.
const hiddenGalleryInput = document.createElement('input');
hiddenGalleryInput.type = 'file';
hiddenGalleryInput.name = 'gallery[]';
hiddenGalleryInput.multiple = true;
hiddenGalleryInput.className = 'hidden';
document.getElementById('productForm').appendChild(hiddenGalleryInput);

document.getElementById('productForm').addEventListener('submit', () => {
    const dt = new DataTransfer();
    galleryFiles.forEach(file => dt.items.add(file));
    hiddenGalleryInput.files = dt.files;
});
</script>
@endpush
