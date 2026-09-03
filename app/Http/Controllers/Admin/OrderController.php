<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PricingConfig;
use App\Models\MaterialPrice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->payment_status, fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->search, fn($q) => $q->where(function($sq) use ($request) {
                $sq->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('customer_email', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'statusHistory.changedBy');
        $materials = MaterialPrice::where('active', true)->get();
        $pricingConfig = PricingConfig::first();
        return view('admin.orders.show', compact('order', 'materials', 'pricingConfig'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'note' => $request->note,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', __('admin.status_updated'));
    }

    public function updatePricing(Request $request, Order $order)
    {
        $validated = $request->validate([
            'artwork_price' => 'required|numeric|min:0',
            'material_price' => 'required|numeric|min:0',
            'labor_price' => 'required|numeric|min:0',
            'digital_copy_price' => 'required|numeric|min:0',
            'shipping_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
        ]);

        $total = array_sum(array_slice(array_values($validated), 0, 5)) - $validated['discount'];
        $order->update(array_merge($validated, ['total_price' => max(0, $total)]));

        return back()->with('success', __('admin.pricing_updated'));
    }
}
