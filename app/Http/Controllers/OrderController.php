<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function create()
    {
        $pricingConfig = \App\Models\PricingConfig::first();
        $materials = \App\Models\MaterialPrice::where('active', true)->get();
        return view('frontend.order.create', compact('pricingConfig', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string',
            'customer_city' => 'nullable|string|max:100',
            'customer_country' => 'nullable|string|max:100',
            'order_type' => 'required|string',
            'text_to_write' => 'nullable|string|max:2000',
            'width_cm' => 'nullable|numeric|min:1|max:500',
            'height_cm' => 'nullable|numeric|min:1|max:500',
            'material' => 'nullable|string',
            'calligraphy_style' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'delivery_method' => 'required|string|in:digital,postal,pickup',
            'reference_images.*' => 'nullable|image|max:5120',
        ]);

        $referenceImages = [];
        if ($request->hasFile('reference_images')) {
            foreach ($request->file('reference_images') as $image) {
                $path = $image->store('orders/references', 'public');
                $referenceImages[] = $path;
            }
        }

        $order = Order::create(array_merge($validated, [
            'reference_images' => $referenceImages,
            'user_id' => auth()->id(),
        ]));

        return redirect()->route('order.success', $order->order_number)
            ->with('success', __('messages.order_submitted'));
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.order.success', compact('order'));
    }

    public function calculatePrice(Request $request)
    {
        $pricingConfig = \App\Models\PricingConfig::first();
        $material = \App\Models\MaterialPrice::find($request->material_id);

        $width = (float) $request->width ?? 0;
        $height = (float) $request->height ?? 0;
        $area = $width * $height;

        $artworkPrice = $area * ($pricingConfig->price_per_cm ?? 5);
        $materialPrice = $area * ($material->price_per_unit ?? 0);
        $laborPrice = $artworkPrice * 0.3;
        $digitalCopyPrice = $request->include_digital ? ($pricingConfig->digital_copy_price ?? 50) : 0;

        $shippingPrice = 0;
        if ($request->delivery === 'postal') {
            $isLocal = in_array(strtolower($request->country ?? ''), ['maroc', 'morocco', 'المغرب']);
            $shippingPrice = $isLocal
                ? ($pricingConfig->shipping_local_price ?? 30)
                : ($pricingConfig->shipping_international_price ?? 150);
        }

        $total = $artworkPrice + $materialPrice + $laborPrice + $digitalCopyPrice + $shippingPrice;

        return response()->json([
            'artwork_price' => round($artworkPrice, 2),
            'material_price' => round($materialPrice, 2),
            'labor_price' => round($laborPrice, 2),
            'digital_copy_price' => round($digitalCopyPrice, 2),
            'shipping_price' => round($shippingPrice, 2),
            'total' => round($total, 2),
            'currency' => $pricingConfig->currency ?? 'MAD',
        ]);
    }
}
