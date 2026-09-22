<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\UploadsToSupabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use UploadsToSupabase;

    /**
     * GET /admin/products
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->when($request->q, fn($q) => $q->where(fn($w) => $w
                ->where('name', 'like', "%{$request->q}%")
                ->orWhere('sku', 'like', "%{$request->q}%")))
            ->when($request->category, fn($q) => $q->whereHas('category', fn($c) => $c->where('slug', $request->category)))
            ->when($request->status !== null && $request->status !== '', fn($q) => $q->where('status', $request->status === 'published'))
            ->when($request->input('sort') === 'name', fn($q) => $q->orderBy('name'))
            ->when($request->input('sort') === 'price_asc', fn($q) => $q->orderByRaw('COALESCE(sale_price, price) asc'))
            ->when($request->input('sort') === 'price_desc', fn($q) => $q->orderByRaw('COALESCE(sale_price, price) desc'))
            ->when(! in_array($request->input('sort'), ['name', 'price_asc', 'price_desc'], true), fn($q) => $q->latest())
            ->paginate(16)
            ->withQueryString();

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.pages.products.index', compact('products', 'categories'));
    }

    /**
     * GET /admin/products/create
     */
    public function create()
    {
        return view('admin.pages.products.create', [
            'product' => new Product(),
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * POST /admin/products
     *
     * Uploads the cover image and every gallery image to Supabase Storage,
     * then stores the returned paths — never the raw files — on the model.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['sku'] = $data['sku'] ?: $this->generateSku($data['name']);
        $data['status'] = $request->boolean('status');
        $data['featured'] = $request->boolean('featured');

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $this->uploadToSupabase($request->file('main_image'), 'products', 1600);
        }

        $product = Product::create($data);

        if ($request->hasFile('gallery')) {
            $paths = $this->uploadManyToSupabase($request->file('gallery'), 'products/gallery', 800);
            foreach ($paths as $i => $path) {
                $product->images()->create([
                    'image' => $path,
                    'sort_order' => $i,
                    'is_primary' => $i === 0 && ! $product->main_image,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('status', "Product \"{$product->name}\" created.");
    }

    /**
     * GET /admin/products/{product}/edit
     */
    public function edit(Product $product)
    {
        $product->load('images');

        return view('admin.pages.products.create', [
            'product' => $product,
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * PUT/PATCH /admin/products/{product}
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status');
        $data['featured'] = $request->boolean('featured');

        if ($request->hasFile('main_image')) {
            $this->deleteFromSupabase($product->main_image);
            $data['main_image'] = $this->uploadToSupabase($request->file('main_image'), 'products', 1600);
        }

        $product->update($data);

        // Remove gallery images the admin unchecked in the edit form.
        if ($request->filled('remove_images')) {
            $toRemove = ProductImage::whereIn('id', $request->input('remove_images'))
                ->where('product_id', $product->id)
                ->get();

            foreach ($toRemove as $image) {
                $this->deleteFromSupabase($image->image);
                $image->delete();
            }
        }

        // Append any newly added gallery images.
        if ($request->hasFile('gallery')) {
            $startOrder = $product->images()->max('sort_order') + 1;
            $paths = $this->uploadManyToSupabase($request->file('gallery'), 'products/gallery', 800);
            foreach ($paths as $i => $path) {
                $product->images()->create([
                    'image' => $path,
                    'sort_order' => $startOrder + $i,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('status', "Product \"{$product->name}\" updated.");
    }

    /**
     * DELETE /admin/products/{product}
     */
    public function destroy(Product $product)
    {
        $this->deleteFromSupabase($product->main_image);
        foreach ($product->images as $image) {
            $this->deleteFromSupabase($image->image);
        }

        $product->delete(); // product_images cascade-deletes via the FK

        return back()->with('status', 'Product deleted.');
    }

    protected function generateSku(string $name): string
    {
        $prefix = strtoupper(Str::substr(Str::slug($name, ''), 0, 8)) ?: 'PRODUCT';
        $sku = $prefix;
        $counter = 1;

        while (Product::where('sku', $sku)->exists()) {
            $sku = $prefix . '-' . $counter++;
        }

        return $sku;
    }
}
