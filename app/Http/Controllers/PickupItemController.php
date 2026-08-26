<?php

namespace App\Http\Controllers;

use App\Models\PickupItem;
use App\Models\PickupRequest;
use Illuminate\Http\Request;

class PickupItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = PickupItem::with('pickupRequest')->get();
        return view('admin.pickup-items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pickupRequests = PickupRequest::all();
        return view('admin.pickup-items.create', compact('pickupRequests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pickup_request_id' => 'required|exists:pickup_requests,pickup_request_id',
            'waste_category_id' => 'required|exists:waste_categories,waste_category_id',
            'weight' => 'required|numeric|min:0',
            'estimated_price' => 'nullable|numeric|min:0',
        ]);

        PickupItem::create($request->all());
        return redirect()->route('admin.pickup-items.index')->with('success', 'Item pickup berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PickupItem $pickupItem)
    {
        $pickupItem->load('pickupRequest');
        return view('admin.pickup-items.show', compact('pickupItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PickupItem $pickupItem)
    {
        $pickupRequests = PickupRequest::all();
        return view('admin.pickup-items.edit', compact('pickupItem', 'pickupRequests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PickupItem $pickupItem)
    {
        $request->validate([
            'pickup_request_id' => 'required|exists:pickup_requests,pickup_request_id',
            'waste_category_id' => 'required|exists:waste_categories,waste_category_id',
            'weight' => 'required|numeric|min:0',
            'estimated_price' => 'nullable|numeric|min:0',
        ]);

        $pickupItem->update($request->all());
        return redirect()->route('admin.pickup-items.index')->with('success', 'Item pickup berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PickupItem $pickupItem)
    {
        $pickupItem->delete();
        return redirect()->route('admin.pickup-items.index')->with('success', 'Item pickup berhasil dihapus');
    }
}
