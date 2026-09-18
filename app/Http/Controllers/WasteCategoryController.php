<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WasteCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wasteCategories = WasteCategory::latest()->get();
        return view('admin.waste-categories.index', compact('wasteCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.waste-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'reward_per_kg' => 'nullable|integer|min:0',
            'point_per_kg' => 'required|integer|min:0',
            'co2_saved_per_kg' => 'nullable|numeric|min:0',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('icons', 'public');
        }

        WasteCategory::create($data);

        return redirect()
            ->route('admin.waste-categories.index')
            ->with('success', 'Jenis sampah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(WasteCategory $wasteCategory)
    {
        $wasteCategory->load('pickupItems');
        return view('admin.waste-categories.show', compact('wasteCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WasteCategory $wasteCategory)
    {
        return view('admin.waste-categories.edit', compact('wasteCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WasteCategory $wasteCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'reward_per_kg' => 'nullable|integer|min:0',
            'point_per_kg' => 'required|integer|min:0',
            'co2_saved_per_kg' => 'nullable|numeric|min:0',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            if ($wasteCategory->icon && Storage::disk('public')->exists($wasteCategory->icon)) {
                Storage::disk('public')->delete($wasteCategory->icon);
            }
            $data['icon'] = $request->file('icon')->store('icons', 'public');
        }

        $wasteCategory->update($data);

        return redirect()
            ->route('admin.waste-categories.index')
            ->with('success', 'Jenis sampah berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WasteCategory $wasteCategory)
    {
        if ($wasteCategory->icon && Storage::disk('public')->exists($wasteCategory->icon)) {
            Storage::disk('public')->delete($wasteCategory->icon);
        }

        $wasteCategory->delete();

        return redirect()
            ->route('admin.waste-categories.index')
            ->with('success', 'Jenis sampah berhasil dihapus');
    }
}