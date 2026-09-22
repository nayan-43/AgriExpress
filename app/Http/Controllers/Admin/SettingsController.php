<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * GET /admin/settings
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'general');

        $store = (object) array_merge([
            'name' => 'AgriExpress',
            'phone' => '+91 98765 43210',
            'timezone' => '(GMT+05:30) India Standard Time',
            'currency' => 'INR — Indian Rupee',
            'weight_unit' => 'Kilogram (kg)',
            'address' => '123 Green Park, Kolkata, West Bengal 700001',
        ], config('store', []));

        $admin = auth('admin')->user();

        return view('admin.pages.settings.index', compact('store', 'tab', 'admin'));
    }

    /**
     * PUT /admin/settings
     */
    public function update(Request $request)
    {
        $tab = $request->input('tab', 'general');
        $admin = auth('admin')->user();

        if ($tab === 'admin_email') {
            $validated = $request->validate([
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin?->id)],
            ]);

            $admin?->update(['email' => $validated['email']]);

            return back()->with('status', 'Admin email updated.');
        }

        if ($tab === 'admin_password') {
            $request->validate([
                'current_password' => ['required', 'current_password:admin'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $admin?->update(['password' => bcrypt($request->input('password'))]);

            return back()->with('status', 'Admin password updated.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'timezone' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'weight_unit' => ['required', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        // Persist the general store settings in your preferred settings-backed store.
        return back()->with('status', 'General settings saved.');
    }
}
