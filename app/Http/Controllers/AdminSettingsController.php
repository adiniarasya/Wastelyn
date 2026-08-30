<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'xp_per_kg' => 'nullable|integer|min:0',
            'xp_per_mission' => 'nullable|integer|min:0',
            'xp_bonus_consistent' => 'nullable|integer|min:0',
            'xp_read_article' => 'nullable|integer|min:0',
            'ai_api_key' => 'nullable|string',
            'ai_model' => 'nullable|string',
        ]);

        $keys = ['app_name', 'city', 'description', 'xp_per_kg', 'xp_per_mission', 'xp_bonus_consistent', 'xp_read_article', 'ai_api_key', 'ai_model'];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}