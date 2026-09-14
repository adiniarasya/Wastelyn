<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\User;
use App\Models\WasteBank;
use Illuminate\Http\Request;

class PickupRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pickups = PickupRequest::with('user', 'wasteBank')->get();
        return view('admin.pickups.index', compact('pickups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'warga')->get();
        $wasteBanks = WasteBank::all();
        return view('admin.pickups.create', compact('users', 'wasteBanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'pickup_method' => 'required|in:pickup,dropoff',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        PickupRequest::create($request->all());
        return redirect()->route('admin.pickup-requests.index')->with('success', 'Pickup request berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PickupRequest $pickupRequest)
    {
        $pickupRequest->load('user', 'wasteBank', 'items');
        return view('admin.pickups.show', compact('pickupRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PickupRequest $pickupRequest)
    {
        $users = User::where('role', 'warga')->get();
        $wasteBanks = WasteBank::all();
        return view('admin.pickups.edit', compact('pickupRequest', 'users', 'wasteBanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PickupRequest $pickupRequest)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'pickup_method' => 'required|in:pickup,dropoff',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,accepted,scheduled,completed,rejected',
        ]);

        $pickupRequest->update($request->all());
        return redirect()->route('admin.pickup-requests.index')->with('success', 'Pickup request berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PickupRequest $pickupRequest)
    {
        $pickupRequest->delete();
        return redirect()->route('admin.pickup-requests.index')->with('success', 'Pickup request berhasil dihapus');
    }

    /**
     * Update status pickup request.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,scheduled,completed,rejected',
        ]);

        $pickup = PickupRequest::findOrFail($id);
        $pickup->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pickup berhasil diupdate menjadi ' . $request->status);
    }

    /**
     * Assign pickup request to mitra.
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'mitra_id' => 'required|exists:users,user_id',
        ]);

        $pickup = PickupRequest::findOrFail($id);
        $pickup->update(['mitra_id' => $request->mitra_id]);

        return redirect()->back()->with('success', 'Pickup berhasil diassign ke mitra');
    }
        public function mitraIndex()
    {
        $available = PickupRequest::with('user', 'wasteBank')
            ->whereNull('mitra_id')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'available_page');

        $mine = PickupRequest::with('user', 'wasteBank')
            ->where('mitra_id', auth()->id())
            ->whereIn('status', ['accepted', 'scheduled'])
            ->latest()
            ->paginate(10, ['*'], 'mine_page');

        return view('mitra.pickups.index', compact('available', 'mine'));
    }

        public function mitraShow(PickupRequest $pickupRequest)
    {
        $pickupRequest->load('user', 'wasteBank');
        return view('mitra.pickups.show', compact('pickupRequest'));
    }
}

