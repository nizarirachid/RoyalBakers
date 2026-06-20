<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'new')->count(),
            'total_users' => User::count(),
            'total_artworks' => Artwork::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_price'),
            'new_messages' => ContactMessage::where('status', 'new')->count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'total_products' => Product::count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentMessages'));
    }
}
