<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('active', true)->get();

        $products = Product::where('status', 'active')
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, fn($q) => $q->where(function($sq) use ($request) {
                $sq->where('name_ar', 'like', "%{$request->search}%")
                  ->orWhere('name_fr', 'like', "%{$request->search}%")
                  ->orWhere('name_en', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(12);

        return view('frontend.shop.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $related = Product::where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();

        return view('frontend.shop.show', compact('product', 'related'));
    }

    public function purchase(Request $request, Product $product)
    {
        if ($product->status !== 'active') {
            return back()->with('error', 'هذا المنتج غير متاح حالياً');
        }

        // Redirect to contact for now - full payment integration requires payment gateway setup
        return redirect()->route('contact')->with('info', 'لإتمام عملية الشراء، يرجى التواصل معنا عبر نموذج التواصل أو واتساب.');
    }

    public function download(Product $product)
    {
        if (!$product->download_file || !Storage::exists($product->download_file)) {
            abort(404, 'الملف غير متوفر');
        }

        return Storage::download($product->download_file, $product->name_ar . '.zip');
    }
}
