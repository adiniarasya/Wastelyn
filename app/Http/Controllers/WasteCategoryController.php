<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WasteCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = WasteCategory::all();
        return view('admin.waste-categories.index', compact('categories'));
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
        $request->validate([
            'name' => 'required|string|max:255|unique:waste_categories,name',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        WasteCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price_per_kg' => $request->price_per_kg,
            'icon' => $request->icon,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.waste-categories.index')->with('success', 'Kategori sampah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(WasteCategory $wasteCategory)
    {
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
        $request->validate([
            'name' => 'required|string|max:255|unique:waste_categories,name,' . $wasteCategory->category_id . ',category_id',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $wasteCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price_per_kg' => $request->price_per_kg,
            'icon' => $request->icon,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.waste-categories.index')->with('success', 'Kategori sampah berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WasteCategory $wasteCategory)
    {
        $wasteCategory->delete();
        return redirect()->route('admin.waste-categories.index')->with('success', 'Kategori sampah berhasil dihapus');
    }

    /**
     * Toggle category status (active/inactive).
     */
    public function toggleStatus(WasteCategory $wasteCategory)
    {
        $category = WasteCategory::findOrFail( $wasteCategory);
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status kategori berhasil diubah menjadi ' . $newStatus);
    }
}
