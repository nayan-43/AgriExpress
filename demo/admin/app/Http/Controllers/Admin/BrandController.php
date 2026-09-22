<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Traits\UploadsToSupabase;

class BrandController extends Controller
{
    use UploadsToSupabase;

    /**
     * GET /admin/brands
     */
    public function index()
    {
        $brands = Brand::withCount('products')->orderBy('name')->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * GET /admin/brands/create
     */
    public function create()
    {
        return view('admin.brands.create', ['brand' => new Brand()]);
    }

    protected function rules(?int $brandId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brandId)],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * POST /admin/brands
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadToSupabase($request->file('logo'), 'brands');
        }

        $brand = Brand::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('status', "Brand \"{$brand->name}\" created.");
    }

    /**
     * GET /admin/brands/{brand}/edit
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.create', compact('brand'));
    }

    /**
     * PUT/PATCH /admin/brands/{brand}
     */
    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate($this->rules($brand->id));
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('logo')) {
            $this->deleteFromSupabase($brand->logo);
            $data['logo'] = $this->uploadToSupabase($request->file('logo'), 'brands');
        }

        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('status', "Brand \"{$brand->name}\" updated.");
    }

    /**
     * DELETE /admin/brands/{brand}
     */
    public function destroy(Brand $brand)
    {
        $this->deleteFromSupabase($brand->logo);
        $brand->delete();

        return back()->with('status', 'Brand deleted.');
    }
}
