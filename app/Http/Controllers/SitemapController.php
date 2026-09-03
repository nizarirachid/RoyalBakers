<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $artworks = Artwork::where('status', 'published')->latest()->get(['slug', 'updated_at']);
        $posts = BlogPost::published()->latest()->get(['slug', 'updated_at']);
        $courses = Course::where('status', 'active')->latest()->get(['slug', 'updated_at']);
        $products = Product::where('status', 'active')->latest()->get(['slug', 'updated_at']);

        return response()->view('sitemap', compact('artworks', 'posts', 'courses', 'products'))
            ->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /register\n\nSitemap: " . url('/sitemap.xml');
        return response($content)->header('Content-Type', 'text/plain');
    }
}
