<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\BlogPost;
use App\Models\Course;
use App\Models\Setting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featuredArtworks = Artwork::where('is_featured', true)
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('active', true)
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $latestPosts = BlogPost::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $activeCourses = Course::where('status', 'active')
            ->take(3)
            ->get();

        return view('frontend.home', compact(
            'featuredArtworks', 'testimonials', 'latestPosts', 'activeCourses'
        ));
    }

    public function about()
    {
        $testimonials = Testimonial::where('active', true)->get();
        return view('frontend.about', compact('testimonials'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function faq()
    {
        $faqs = \App\Models\Faq::where('active', true)->orderBy('sort_order')->get();
        return view('frontend.faq', compact('faqs'));
    }

    public function setLocale(string $locale)
    {
        $supported = ['ar', 'fr', 'en'];
        if (in_array($locale, $supported)) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
