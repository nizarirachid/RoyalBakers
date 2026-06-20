<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialPrice;
use App\Models\PricingConfig;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $pricingConfig = PricingConfig::firstOrCreate([]);
        $materials = MaterialPrice::all();
        return view('admin.settings.index', compact('settings', 'pricingConfig', 'materials'));
    }

    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
        return back()->with('success', __('admin.settings_saved'));
    }

    public function updatePricing(Request $request)
    {
        $validated = $request->validate([
            'price_per_cm' => 'required|numeric|min:0',
            'digital_copy_price' => 'required|numeric|min:0',
            'shipping_local_price' => 'required|numeric|min:0',
            'shipping_international_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        PricingConfig::first()->update($validated);
        return back()->with('success', __('admin.pricing_saved'));
    }

    public function storeMaterial(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price_per_unit' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);

        MaterialPrice::create($validated);
        return back()->with('success', __('admin.material_added'));
    }

    public function destroyMaterial(MaterialPrice $material)
    {
        $material->delete();
        return back()->with('success', __('admin.material_deleted'));
    }
}
