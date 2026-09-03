<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $artworks = Artwork::with('category')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->latest()
            ->paginate(20);

        $categories = Category::where('type', 'artwork')->get();
        return view('admin.artworks.index', compact('artworks', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('active', true)->get();
        return view('admin.artworks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'calligraphy_style' => 'required|string',
            'material' => 'required|string',
            'width_cm' => 'nullable|numeric',
            'height_cm' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'is_for_sale' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|string',
            'main_image' => 'required|image|max:10240',
            'gallery_images.*' => 'nullable|image|max:10240',
            'text_content' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($request->title_ar . '-' . time());

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('artworks', 'public');
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $galleryImages[] = $img->store('artworks/gallery', 'public');
            }
        }
        $validated['gallery_images'] = $galleryImages;

        Artwork::create($validated);

        return redirect()->route('admin.artworks.index')->with('success', __('admin.artwork_created'));
    }

    public function edit(Artwork $artwork)
    {
        $categories = Category::where('active', true)->get();
        return view('admin.artworks.edit', compact('artwork', 'categories'));
    }

    public function update(Request $request, Artwork $artwork)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'calligraphy_style' => 'required|string',
            'material' => 'required|string',
            'width_cm' => 'nullable|numeric',
            'height_cm' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'is_for_sale' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|string',
            'text_content' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('artworks', 'public');
        }

        $artwork->update($validated);
        return redirect()->route('admin.artworks.index')->with('success', __('admin.artwork_updated'));
    }

    public function destroy(Artwork $artwork)
    {
        $artwork->delete();
        return redirect()->route('admin.artworks.index')->with('success', __('admin.artwork_deleted'));
    }
}
