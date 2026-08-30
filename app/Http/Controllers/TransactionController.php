<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\PickupRequest;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'warga')->get();

        $pickupRequests = PickupRequest::where('status', 'completed')->get();

        $redemptions = RewardRedemption::where('status', 'approved')->get();

        return view(
            'admin.transactions.create',
            compact('users', 'pickupRequests', 'redemptions')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'pickup_request_id' => 'nullable|exists:pickup_requests,pickup_request_id',
            'redemption_id' => 'nullable|exists:reward_redemptions,redemption_id',
            'type' => 'required|in:earn,redeem',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        Transaction::create($validated);

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'pickupRequest', 'redemption');

        return view(
            'admin.transactions.show',
            compact('transaction')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $users = User::where('role', 'warga')->get();

        $pickupRequests = PickupRequest::where('status', 'completed')->get();

        $redemptions = RewardRedemption::where('status', 'approved')->get();

        return view(
            'admin.transactions.edit',
            compact(
                'transaction',
                'users',
                'pickupRequests',
                'redemptions'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'pickup_request_id' => 'nullable|exists:pickup_requests,pickup_request_id',
            'redemption_id' => 'nullable|exists:reward_redemptions,redemption_id',
            'type' => 'required|in:earn,redeem',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $transaction->update($validated);

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Update transaction status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $transaction = Transaction::findOrFail($id);

        $transaction->update([
            'status' => $request->status
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status transaksi berhasil diupdate');
    }
}