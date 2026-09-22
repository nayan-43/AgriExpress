<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\UploadsToSupabase;

class CategoryController extends Controller
{
    use UploadsToSupabase;

    /**
     * GET /admin/categories
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('products')
            ->with('parent')
            ->when($request->filled('q'), fn($q) => $q->where(fn($w) => $w
                ->where('name', 'like', '%' . $request->string('q') . '%')
                ->orWhere('slug', 'like', '%' . $request->string('q') . '%')))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->input('status') === 'active'))
            ->when($request->input('sort') === 'name_desc', fn($q) => $q->orderByDesc('name'))
            ->when($request->input('sort') === 'products', fn($q) => $q->orderByDesc('products_count'))
            ->when(! in_array($request->input('sort'), ['name_desc', 'products'], true), fn($q) => $q->orderBy('sort_order')->orderBy('name'))
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.categories.index', compact('categories'));
    }

    /**
     * GET /admin/categories/create
     */
    public function create()
    {
        return view('admin.pages.categories.create', [
            'category' => new Category(),
            'parents' => Category::topLevel()->orderBy('name')->get(),
        ]);
    }

    /**
     * POST /admin/categories
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadToSupabase($request->file('image'), 'categories');
        }
        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadToSupabase($request->file('banner'), 'categories/banners');
        }

        $category = Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', "Category \"{$category->name}\" created.");
    }

    /**
     * GET /admin/categories/{category}/edit
     */
    public function edit(Category $category)
    {
        return view('admin.pages.categories.create', [
            'category' => $category,
            'parents' => Category::topLevel()->where('id', '!=', $category->id)->orderBy('name')->get(),
        ]);
    }

    /**
     * PUT/PATCH /admin/categories/{category}
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $this->deleteFromSupabase($category->image);
            $data['image'] = $this->uploadToSupabase($request->file('image'), 'categories');
        }
        if ($request->hasFile('banner')) {
            $this->deleteFromSupabase($category->banner);
            $data['banner'] = $this->uploadToSupabase($request->file('banner'), 'categories/banners');
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', "Category \"{$category->name}\" updated.");
    }

    /**
     * DELETE /admin/categories/{category}
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => 'Move or delete this category\'s products before deleting it.']);
        }

        $this->deleteFromSupabase($category->image);
        $this->deleteFromSupabase($category->banner);
        $category->delete();

        return back()->with('status', 'Category deleted.');
    }
}
