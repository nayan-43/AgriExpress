<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\UploadsToSupabase;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use UploadsToSupabase;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::query()->orderBy('sort_order')->orderByDesc('id')->paginate(15);

        return view('admin.pages.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.banners.create', ['banner' => new Banner]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['status'] = $request->boolean('status');
        $data['image'] = $this->uploadToSupabase($request->file('image'), 'banners', 1800);
        $banner = Banner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('status', "Banner \"{$banner->title}\" created.");
    }

    /**
     * Display the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.pages.banners.create', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request, false);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('image')) {
            $this->deleteFromSupabase($banner->image);
            $data['image'] = $this->uploadToSupabase($request->file('image'), 'banners', 1800);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('status', "Banner \"{$banner->title}\" updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $this->deleteFromSupabase($banner->image);
        $banner->delete();

        return back()->with('status', 'Banner deleted.');
    }

    protected function validated(Request $request, bool $imageRequired = true): array
    {
        return $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cta_label' => ['nullable', 'string', 'max:100'],
            'cta_url' => ['nullable', 'string', 'max:500', 'regex:/^(\/|https?:\/\/)/i'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['sometimes', 'boolean'],
        ]);
    }
}
