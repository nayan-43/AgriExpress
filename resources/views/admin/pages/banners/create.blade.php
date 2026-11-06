@extends('admin.layouts.admin')

@section('title', $banner->exists ? 'Edit banner' : 'Add banner')
@section('active', 'banners')

@section('content')
    <form action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @if ($banner->exists) @method('PUT') @endif

        <div class="flex flex-wrap gap-3 items-center justify-between mb-5">
            <div>
                <h1 class="text-2xl font-bold">{{ $banner->exists ? 'Edit banner' : 'Add banner' }}</h1>
                <p class="muted text-sm mt-1">Create a homepage hero slide like the attached design</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.banners.index') }}" class="surface border rounded-lg px-4 h-10 text-sm flex items-center">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg px-4 h-10 text-sm font-medium">Save banner</button>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[13px] px-4 py-3">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-5">
            <div class="surface border rounded-2xl p-5 space-y-4 text-sm">
                <div>
                    <label for="eyebrow" class="block mb-1.5 font-medium">Eyebrow</label>
                    <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}"
                        class="w-full border bd rounded-lg px-3 h-10" placeholder="LIMITED TIME">
                </div>
                <div>
                    <label for="title" class="block mb-1.5 font-medium">Title <span class="text-red-500">*</span></label>
                    <textarea id="title" name="title" rows="2" required class="w-full border bd rounded-lg px-3 py-2"
                        placeholder="Autumn Style&#10;Refresh">{{ old('title', $banner->title) }}</textarea>
                    <p class="muted text-xs mt-1">Use a new line to control the title break.</p>
                </div>
                <div>
                    <label for="description" class="block mb-1.5 font-medium">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full border bd rounded-lg px-3 py-2"
                        placeholder="New season arrivals across fashion, footwear and accessories.">{{ old('description', $banner->description) }}</textarea>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="cta_label" class="block mb-1.5 font-medium">Button label</label>
                        <input id="cta_label" name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}"
                            class="w-full border bd rounded-lg px-3 h-10" placeholder="Explore Collection">
                    </div>
                    <div>
                        <label for="cta_url" class="block mb-1.5 font-medium">Button URL</label>
                        <input id="cta_url" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}"
                            class="w-full border bd rounded-lg px-3 h-10" placeholder="/shop?category=women">
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="sort_order" class="block mb-1.5 font-medium">Display order</label>
                        <input id="sort_order" name="sort_order" type="number" min="0"
                            value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
                            class="w-full border bd rounded-lg px-3 h-10">
                    </div>
                    <label class="flex items-center gap-2 self-end pb-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="w-4 h-4 rounded accent-blue-600"
                            @checked(old('status', $banner->status ?? true))>
                        Active on homepage
                    </label>
                </div>
            </div>

            <div class="space-y-5">
                <div class="surface border rounded-2xl p-5">
                    <h2 class="font-semibold mb-3">Hero image <span class="text-red-500">*</span></h2>
                    <label for="image" class="block relative border-2 border-dashed rounded-xl text-center cursor-pointer overflow-hidden"
                        style="border-color:#c7d6f5;background:var(--bg)">
                        <div id="imagePreviewWrap" class="{{ $banner->image_url ? '' : 'hidden' }}">
                            <img id="imagePreview" src="{{ $banner->image_url }}" class="w-full h-40 object-cover">
                        </div>
                        <div id="imagePlaceholder" class="py-8 {{ $banner->image_url ? 'hidden' : '' }}">
                            <i class="fa-solid fa-panorama text-xl text-blue-500"></i>
                            <p class="text-[13px] mt-2 font-medium">Click to upload</p>
                            <p class="text-[11px] muted mt-0.5">Wide image, ideally 900 × 560px or larger</p>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="hidden" {{ $banner->exists ? '' : 'required' }}>
                    </label>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const bannerImageInput = document.getElementById('image');
        bannerImageInput?.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
            document.getElementById('imagePreviewWrap').classList.remove('hidden');
            document.getElementById('imagePlaceholder').classList.add('hidden');
        });
    </script>
@endpush
