<?php

namespace App\Http\Controllers;

use App\Models\WasteCategory;
use App\Models\MitraWastePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraWastePriceController extends Controller
{
    /**
     * Daftar semua kategori + harga override mitra.
     */
    public function index()
    {
        $mitraId = Auth::id();

        $categories = WasteCategory::orderBy('name')->get();

        $prices = MitraWastePrice::where('mitra_id', $mitraId)
            ->get()
            ->keyBy('category_id');

        return view('mitra.harga-sampah.index', compact('categories', 'prices'));
    }

    /**
     * Simpan / update harga override.
     */
    public function update(Request $request, $categoryId)
    {
        $request->validate([
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        $mitraId = Auth::id();

        WasteCategory::findOrFail($categoryId);

        MitraWastePrice::updateOrCreate(
            [
                'mitra_id' => $mitraId,
                'category_id' => $categoryId,
            ],
            [
                'price_per_kg' => $request->price_per_kg,
            ]
        );

        return back()->with('success', 'Harga berhasil disimpan.');
    }

    /**
     * Hapus harga override (kembali ke default admin).
     */
    public function destroy($categoryId)
    {
        $mitraId = Auth::id();

        MitraWastePrice::where('mitra_id', $mitraId)
            ->where('category_id', $categoryId)
            ->delete();

        return back()->with('success', 'Harga override dihapus. Kembali ke default admin.');
    }
}