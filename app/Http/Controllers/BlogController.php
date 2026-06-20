<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('active', true)->get();

        $posts = BlogPost::published()
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->search, fn($q) => $q->where(function($sq) use ($request) {
                $sq->where('title_ar', 'like', "%{$request->search}%")
                  ->orWhere('title_fr', 'like', "%{$request->search}%")
                  ->orWhere('title_en', 'like', "%{$request->search}%");
            }))
            ->latest('published_at')
            ->paginate(9);

        return view('frontend.blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        $post->increment('views');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)->get();

        return view('frontend.blog.show', compact('post', 'related'));
    }
}
