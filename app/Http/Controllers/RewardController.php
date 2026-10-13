<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RewardController extends Controller
{

    public function mitraIndex()
    {
        $rewards = Reward::all();
        return view('mitra.rewards.index', compact('rewards'));
    }

    public function mitraCreate()
    {
        return view('mitra.rewards.create');
    }

    public function mitraStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'type' => 'required|in:instant,manual,pickup',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $data['image'] = $filename;
        }

        Reward::create($data);

        return redirect()
            ->route('mitra.rewards.index')
            ->with('success', 'Reward berhasil ditambahkan');
    }

    public function mitraShow(Reward $reward)
    {
        return view('mitra.rewards.show', compact('reward'));
    }

    public function mitraEdit(Reward $reward)
    {
        return view('mitra.rewards.edit', compact('reward'));
    }

    public function mitraUpdate(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'type' => 'required|in:instant,manual,pickup',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($reward->image && File::exists(public_path('assets/images/' . $reward->image))) {
                File::delete(public_path('assets/images/' . $reward->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $data['image'] = $filename;
        }

        $reward->update($data);

        return redirect()
            ->route('mitra.rewards.index')
            ->with('success', 'Reward berhasil diupdate');
    }

    public function mitraDestroy(Reward $reward)
    {
        if ($reward->image && File::exists(public_path('assets/images/' . $reward->image))) {
            File::delete(public_path('assets/images/' . $reward->image));
        }

        $reward->delete();

        return redirect()
            ->route('mitra.rewards.index')
            ->with('success', 'Reward berhasil dihapus');
    }

    public function userIndex()
    {
        $rewards = Reward::where('status', 'available')
            ->orderBy('point_required')
            ->get();

        $user = auth()->user();

        $myRedemptions = \App\Models\RewardRedemption::with('reward')
            ->where('user_id', $user->user_id)
            ->latest()
            ->limit(10)
            ->get();

        return view('user.rewards.index', compact('rewards', 'user', 'myRedemptions'));
    }

    public function userShow($id)
    {
        $reward = Reward::findOrFail($id);
        $user = auth()->user();

        return view('user.rewards.show', compact('reward', 'user'));
    }

    public function userRedeem(Request $request, $id)
    {
        $user = auth()->user();
        $reward = Reward::findOrFail($id);

        if ($reward->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'Reward tidak tersedia.'], 400);
        }

        if ($reward->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok reward habis.'], 400);
        }

        if ($user->points < $reward->point_required) {
            return response()->json(['success' => false, 'message' => 'Poin tidak cukup.'], 400);
        }

        $type = $reward->type ?? 'instant';
        $isEwallet = preg_match('/e-wallet|ewallet|dana|gopay|ovo|shopeepay/i', $reward->name);

        if ($type === 'manual') {
            $request->validate([
                'phone_number' => 'required|string|min:8|max:20',
            ]);

            if ($isEwallet) {
                $request->validate([
                    'wallet_provider' => 'required|string|in:DANA,GoPay,OVO,ShopeePay,LinkAja',
                ]);
            }
        } elseif ($type === 'pickup') {
            $request->validate([
                'pickup_date' => 'required|date|after_or_equal:today',
            ]);
        }

        \DB::beginTransaction();
        try {
            $user->points -= $reward->point_required;
            $user->save();

            $reward->stock -= 1;
            $reward->save();

            $voucherCode = null;
            $pickupCode = null;
            $pickupDate = null;
            $pickupExpiresAt = null;
            $deliveryNote = null;

            if ($type === 'instant') {
                $voucherCode = strtoupper('WL-' . bin2hex(random_bytes(4)) . '-' . bin2hex(random_bytes(2)));
                $status = 'completed';
            } elseif ($type === 'manual') {
                if ($isEwallet) {
                    $deliveryNote = 'Saldo ' . $request->wallet_provider . ' telah terkirim ke ' . $request->phone_number;
                } else {
                    $deliveryNote = 'Pulsa telah terkirim ke ' . $request->phone_number;
                }
                $status = 'completed';
            } else {
                $pickupCode = strtoupper('AMBIL-' . bin2hex(random_bytes(3)));
                $pickupDate = $request->pickup_date;
                $pickupExpiresAt = now()->addDays(30);
                $status = 'pending';
            }

            $redemption = \App\Models\RewardRedemption::create([
                'user_id' => $user->user_id,
                'reward_id' => $reward->reward_id,
                'status' => $status,
                'processed_at' => $status === 'completed' ? now() : null,
                'redeemed_at' => now(),
                'voucher_code' => $voucherCode,
                'pickup_code' => $pickupCode,
                'pickup_date' => $pickupDate,
                'pickup_expires_at' => $pickupExpiresAt,
                'delivery_note' => $deliveryNote,
            ]);

            \App\Models\Transaction::create([
                'user_id' => $user->user_id,
                'redemption_id' => $redemption->redemption_id,
                'type' => 'redeem',
                'points' => $reward->point_required,
                'description' => 'Tukar reward: ' . $reward->name,
            ]);

            $mitras = \App\Models\User::where('role', 'mitra')->get();
            foreach ($mitras as $mitra) {
                try {
                    $message = 'Warga ' . $user->name . ' menukar "' . $reward->name . '".';
                    if ($type === 'manual') {
                        if ($isEwallet) {
                            $message .= ' Kirim ' . $request->wallet_provider . ' ke: ' . $request->phone_number;
                        } else {
                            $message .= ' Kirim ke: ' . $request->phone_number;
                        }
                    } elseif ($type === 'pickup') {
                        $message .= ' Kode ambil: ' . $pickupCode;
                    }

                    \App\Models\Notification::create([
                        'user_id' => $mitra->user_id,
                        'title' => 'Bukti Penukaran Reward',
                        'message' => $message,
                        'type' => 'success',
                        'link' => route('mitra.rewards.redemptions'),
                        'is_read' => false,
                    ]);
                } catch (\Exception $e) {}
            }

            \DB::commit();

            $response = ['success' => true];

            if ($type === 'manual') {
                $response['phone_number'] = $request->phone_number;
            } elseif ($type === 'pickup') {
                $response['pickup_code'] = $pickupCode;
                $response['pickup_expires_at'] = $pickupExpiresAt->format('d M Y');
            }

            return response()->json($response);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal tukar reward: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function userRedemptionShow($id)
{
    $redemption = \App\Models\RewardRedemption::with('user', 'reward')
        ->where('redemption_id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    return view('user.rewards.redemption-show', compact('redemption'));
}

    public function mitraRedemptions(Request $request)
    {
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');

        $query = \App\Models\RewardRedemption::with('user', 'reward');

        // Filter by status
        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        }

        // Filter by reward type
        if ($type !== 'all') {
            $query->whereHas('reward', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        // Sorting: pending dulu, baru latest
        $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END ASC")
              ->orderBy('redeemed_at', 'desc');

        $redemptions = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => \App\Models\RewardRedemption::count(),
            'pending_pickup' => \App\Models\RewardRedemption::where('status', 'pending')->count(),
            'completed' => \App\Models\RewardRedemption::where('status', 'completed')->count(),
            'today' => \App\Models\RewardRedemption::whereDate('redeemed_at', today())->count(),
            'pulsa' => \App\Models\RewardRedemption::whereHas('reward', fn($q) => $q->where('type', 'manual'))->count(),
            'voucher' => \App\Models\RewardRedemption::whereHas('reward', fn($q) => $q->where('type', 'instant'))->count(),
            'pickup' => \App\Models\RewardRedemption::whereHas('reward', fn($q) => $q->where('type', 'pickup'))->count(),
        ];

        return view('mitra.rewards.redemptions', compact('redemptions', 'stats', 'status', 'type'));
    }

    public function mitraVerifyPickup(Request $request, $id)
    {
        $request->validate([
            'pickup_code' => 'required|string',
        ]);

        $redemption = \App\Models\RewardRedemption::findOrFail($id);

        if ($redemption->status !== 'pending') {
            return back()->with('error', 'Penukaran ini sudah selesai.');
        }

        if (strtoupper($request->pickup_code) !== strtoupper($redemption->pickup_code)) {
            return back()->with('error', 'Kode pengambilan salah. Cek ulang.');
        }

        if ($redemption->pickup_expires_at && now()->greaterThan($redemption->pickup_expires_at)) {
            return back()->with('error', 'Kode sudah kadaluarsa.');
        }

        $redemption->update([
            'status' => 'completed',
            'processed_at' => now(),
            'picked_up_at' => now(),
        ]);

        try {
            \App\Models\Notification::create([
                'user_id' => $redemption->user_id,
                'title' => 'Barang Berhasil Diambil',
                'message' => 'Penukaran reward "' . ($redemption->reward->name ?? '-') . '" telah selesai.',
                'type' => 'success',
                'link' => route('user.rewards.index'),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Pengambilan barang berhasil diverifikasi.');
    }
        public function mitraRedemptionShow($id)
    {
        $redemption = \App\Models\RewardRedemption::with('user', 'reward')
            ->findOrFail($id);

        return view('mitra.rewards.redemption-show', compact('redemption'));
    }
}