<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Tampilkan riwayat transaksi (admin / mitra).
     */
    public function index()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(15);

        return view('mitra.transactions.index', compact('transactions'));
    }

    /**
     * Verifikasi setoran oleh Mitra → status completed, poin masuk.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pickup_request_id' => 'required|exists:pickup_requests,pickup_request_id',
            'weight_kg' => 'required|numeric|min:0.1',
        ]);

        DB::beginTransaction();
        try {
            $pickup = PickupRequest::where('pickup_request_id', $request->pickup_request_id)
                ->where('mitra_id', Auth::id())
                ->whereIn('status', ['accepted', 'scheduled'])
                ->firstOrFail();

    
            $pickup->weight_kg = $request->weight_kg;
            $pickup->calculateRewards();


            $pickup->status = 'completed';
            $pickup->verified_at = now();
            $pickup->save();


            $user = User::find($pickup->user_id);
            if ($user) {
                $user->points = ($user->points ?? 0) + $pickup->points_earned;
                $user->xp = ($user->xp ?? 0) + $pickup->xp_earned;
                $user->updateLevel();
                $user->save();
                Transaction::create([
                    'user_id' => $user->user_id,
                    'pickup_request_id' => $pickup->pickup_request_id,
                    'type' => 'earn',
                    'points' => $pickup->points_earned,
                    'description' => "Setoran {$pickup->wasteCategory->name} {$pickup->weight_kg}kg - diverifikasi mitra",
                ]);
            }

            DB::commit();

            return redirect()
                ->route('mitra.pickup-requests.index')
                ->with('success', 'Setoran berhasil diverifikasi! Poin & XP telah ditambahkan ke warga.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('mitra.transactions.show', compact('transaction'));
    }
}