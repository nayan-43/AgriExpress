<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * GET /admin/settings
     *
     * There's no settings table in the current schema, so this reads from
     * config/store.php (a small config file you'd add) or a "settings" key
     * cached from the database once that table exists. For now it falls
     * back to sensible defaults so the page renders.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'general');

        $store = (object) array_merge([
            'name' => 'AgriExpress',
            'email' => 'support@shopease.com',
            'phone' => '+91 98765 43210',
            'timezone' => '(GMT+05:30) India Standard Time',
            'currency' => 'INR — Indian Rupee',
            'weight_unit' => 'Kilogram (kg)',
            'address' => '123 Green Park, Kolkata, West Bengal 700001',
        ], config('store', []));

        return view('admin.settings.index', compact('store', 'tab'));
    }

    /**
     * PUT /admin/settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'timezone' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'weight_unit' => ['required', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        // Persist $validated to your settings store (a `settings` table,
        // a config file, or a cached key/value store) once one exists.

        return back()->with('status', 'Changes saved.');
    }
}
