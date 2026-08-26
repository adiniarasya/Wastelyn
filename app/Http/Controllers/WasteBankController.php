<?php

namespace App\Http\Controllers;

use App\Models\WasteBank;
use App\Models\User;
use Illuminate\Http\Request;

class WasteBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wasteBanks = WasteBank::with('mitra')->get();
        return view('admin.waste-banks.index', compact('wasteBanks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mitras = User::where('role', 'mitra')->get();
        return view('admin.waste-banks.create', compact('mitras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mitra_id' => 'nullable|exists:users,user_id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        WasteBank::create($request->all());
        return redirect()->route('admin.waste-banks.index')->with('success', 'Bank sampah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(WasteBank $wasteBank)
    {
        $wasteBank->load('mitra', 'pickupRequests');
        return view('admin.waste-banks.show', compact('wasteBank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WasteBank $wasteBank)
    {
        $mitras = User::where('role', 'mitra')->get();
        return view('admin.waste-banks.edit', compact('wasteBank', 'mitras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WasteBank $wasteBank)
    {
        $request->validate([
            'mitra_id' => 'nullable|exists:users,user_id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $wasteBank->update($request->all());
        return redirect()->route('admin.waste-banks.index')->with('success', 'Bank sampah berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WasteBank $wasteBank)
    {
        $wasteBank->delete();
        return redirect()->route('admin.waste-banks.index')->with('success', 'Bank sampah berhasil dihapus');
    }
}
