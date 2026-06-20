<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->paginate(10);
        $enrollments = $user->enrollments()->with('course')->latest()->get();
        return view('frontend.profile.index', compact('user', 'orders', 'enrollments'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'preferred_language' => 'nullable|in:ar,fr,en',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        if ($validated['preferred_language'] ?? null) {
            session(['locale' => $validated['preferred_language']]);
        }

        return back()->with('success', __('messages.profile_updated'));
    }
}
