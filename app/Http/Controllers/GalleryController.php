<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', 'artwork')->where('active', true)->get();

        $artworks = Artwork::where('status', 'published')
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->style, fn($q) => $q->where('calligraphy_style', $request->style))
            ->when($request->material, fn($q) => $q->where('material', $request->material))
            ->latest()
            ->paginate(12);

        return view('frontend.gallery.index', compact('artworks', 'categories'));
    }

    public function show(string $slug)
    {
        $artwork = Artwork::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $artwork->increment('views');

        $related = Artwork::where('status', 'published')
            ->where('category_id', $artwork->category_id)
            ->where('id', '!=', $artwork->id)
            ->take(4)->get();

        return view('frontend.gallery.show', compact('artwork', 'related'));
    }
}
