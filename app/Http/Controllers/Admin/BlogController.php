<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index() {
        $posts = BlogPost::with('author')->latest()->paginate(20);
        return view('admin.blog.index', compact('posts'));
    }

    public function create() {
        $categories = Category::where('active', true)->get();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_ar' => 'nullable|string',
            'excerpt_ar' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|image|max:5120',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($request->title_ar . '-' . time());

        if ($request->validated('status') === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        BlogPost::create($validated);
        return redirect()->route('admin.blog.index')->with('success', 'تم نشر المقال بنجاح.');
    }

    public function edit(BlogPost $blog) {
        $categories = Category::where('active', true)->get();
        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, BlogPost $blog) {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'content_ar' => 'nullable|string',
            'excerpt_ar' => 'nullable|string|max:500',
            'status' => 'required|string',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->update($validated);
        return redirect()->route('admin.blog.index')->with('success', 'تم تحديث المقال.');
    }

    public function destroy(BlogPost $blog) {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'تم حذف المقال.');
    }
}
