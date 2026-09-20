<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\User;
use App\Models\WasteBank;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickupRequestController extends Controller
{
    public function index()
    {
        $pickups = PickupRequest::with('user', 'wasteBank')->get();
        return view('admin.pickups.index', compact('pickups'));
    }

    public function create()
    {
        $users = User::where('role', 'warga')->get();
        $wasteBanks = WasteBank::all();
        return view('admin.pickups.create', compact('users', 'wasteBanks'));
    }

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

    public function show(PickupRequest $pickupRequest)
    {
        $pickupRequest->load('user', 'wasteBank', 'items');
        return view('admin.pickups.show', compact('pickupRequest'));
    }

    public function edit(PickupRequest $pickupRequest)
    {
        $users = User::where('role', 'warga')->get();
        $wasteBanks = WasteBank::all();
        return view('admin.pickups.edit', compact('pickupRequest', 'users', 'wasteBanks'));
    }

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

    public function destroy(PickupRequest $pickupRequest)
    {
        $pickupRequest->delete();
        return redirect()->route('admin.pickup-requests.index')->with('success', 'Pickup request berhasil dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,scheduled,completed,rejected',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
        ]);

        $pickup = PickupRequest::findOrFail($id);
        $mitraId = auth()->id();

        switch ($request->status) {
            case 'accepted':
                if ($pickup->mitra_id !== null && $pickup->mitra_id !== $mitraId) {
                    return back()->with('error', 'Setoran ini sudah diambil mitra lain.');
                }
                $pickup->mitra_id = $mitraId;
                $pickup->status = 'accepted';
                break;

            case 'scheduled':
                $pickup->pickup_date = $request->pickup_date ?? $pickup->pickup_date;
                $pickup->pickup_time = $request->pickup_time ?? $pickup->pickup_time;
                $pickup->status = 'scheduled';
                break;

            case 'rejected':
                $pickup->mitra_id = null;
                $pickup->status = 'pending';
                break;

            case 'completed':
                if ($pickup->status !== 'completed') {
                    $pickup->status = 'completed';

                    $xpGained = 20 * ($pickup->weight_kg ?? 0);

                    if ($pickup->user) {
                        $pickup->user->addXp($xpGained);
                    }

                    if ($pickup->user) {
                        $pickup->user->addPoints(100 * ($pickup->weight_kg ?? 0));
                    }
                }
                break;
            default:
                $pickup->status = $request->status;
                break;
        }

        $pickup->save();

        return redirect()->back()->with('success', 'Status pickup berhasil diupdate menjadi ' . $pickup->status);
    }

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
        $available = PickupRequest::with('user', 'wasteBank', 'wasteCategory')
            ->whereNull('mitra_id')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'available_page');

        $mine = PickupRequest::with('user', 'wasteBank', 'wasteCategory')
            ->where('mitra_id', auth()->id())
            ->whereIn('status', ['accepted', 'scheduled'])
            ->latest()
            ->paginate(10, ['*'], 'mine_page');

        return view('mitra.pickups.index', compact('available', 'mine'));
    }

    public function mitraShow(PickupRequest $pickupRequest)
    {
        if ($pickupRequest->mitra_id !== null && $pickupRequest->mitra_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak melihat setoran ini.');
        }

        $pickupRequest->load('user', 'wasteBank', 'wasteCategory', 'mitra');
        return view('mitra.pickups.show', compact('pickupRequest'));
    }

    public function userIndex()
    {
        $pickups = PickupRequest::where('user_id', Auth::id())
            ->with('wasteCategory', 'wasteBank', 'mitra')
            ->latest()
            ->paginate(10);

        $totalBerat = PickupRequest::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->sum('weight_kg') ?? 0;

        $totalPoin = PickupRequest::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->sum('points_earned') ?? 0;

        $totalCO2 = PickupRequest::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->sum('co2_saved') ?? 0;

        return view('user.pickups.index', compact('pickups', 'totalBerat', 'totalPoin', 'totalCO2'));
    }

    public function userCreate()
    {
        $wasteCategories = WasteCategory::all();
        $wasteBanks = WasteBank::all();

        return view('user.pickups.create', compact('wasteCategories', 'wasteBanks'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'waste_category_id' => 'required|exists:waste_categories,category_id',
            'weight_kg' => 'required|numeric|min:0.1|max:1000',
            'pickup_method' => 'required|in:pickup,dropoff',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'address' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            PickupRequest::create([
                'user_id' => Auth::id(),
                'bank_id' => $request->bank_id,
                'waste_category_id' => $request->waste_category_id,
                'weight_kg' => $request->weight_kg,
                'pickup_method' => $request->pickup_method,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'address' => $request->address,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            return redirect()
                ->route('user.pickup-requests.index')
                ->with('success', 'Setoran berhasil diajukan! Tunggu verifikasi mitra.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function userShow(PickupRequest $pickupRequest)
    {
        if ($pickupRequest->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat setoran ini.');
        }

        $pickupRequest->load('wasteCategory', 'wasteBank', 'mitra');

        return view('user.pickups.show', compact('pickupRequest'));
    }

    public function userDestroy(PickupRequest $pickupRequest)
    {
        if ($pickupRequest->user_id !== Auth::id()) {
            abort(403);
        }

        if ($pickupRequest->status !== 'pending') {
            return back()->with('error', 'Setoran yang sudah diproses tidak bisa dibatalkan.');
        }

        $pickupRequest->delete();

        return redirect()
            ->route('user.pickup-requests.index')
            ->with('success', 'Pengajuan setoran berhasil dibatalkan.');
    }
}