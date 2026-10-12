<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Tambahkan ini untuk hapus gambar lama

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
        // 1. Validasi Input (Ubah validasi image menjadi file)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar max 2MB
            'status' => 'required|in:available,unavailable',
        ]);

        $data = $request->all();

        // 2. Proses Upload Gambar
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Pindahkan ke folder public/assets/images
            $file->move(public_path('assets/images'), $filename);
            // Simpan HANYA nama filenya ke database
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
        // 1. Validasi Input (Ubah validasi image menjadi file)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        $data = $request->all();

        // 2. Proses Upload Gambar Baru (Jika ada)
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
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
        // Hapus file gambar dari folder saat data dihapus
        if ($reward->image && File::exists(public_path('assets/images/' . $reward->image))) {
            File::delete(public_path('assets/images/' . $reward->image));
        }

        $reward->delete();

        return redirect()
            ->route('mitra.rewards.index')
            ->with('success', 'Reward berhasil dihapus');
    }

    // ============================================================
    // USER — Reward Center
    // ============================================================

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

    public function userRedeem($id)
    {
        $user = auth()->user();
        $reward = Reward::findOrFail($id);

        if ($reward->status !== 'available') {
            return back()->with('error', 'Reward ini sedang tidak tersedia.');
        }

        if ($reward->stock <= 0) {
            return back()->with('error', 'Stok reward habis.');
        }

        if ($user->points < $reward->point_required) {
            return back()->with('error', 'Poin kamu tidak cukup. Butuh ' . number_format($reward->point_required) . ' poin.');
        }

        \DB::beginTransaction();
        try {
            $user->points -= $reward->point_required;
            $user->save();

            $reward->stock -= 1;
            $reward->save();

            $redemption = \App\Models\RewardRedemption::create([
                'user_id' => $user->user_id,
                'reward_id' => $reward->reward_id,
                'status' => 'pending',
                'redeemed_at' => now(),
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
                    \App\Models\Notification::create([
                        'user_id' => $mitra->user_id,
                        'title' => 'Penukaran Reward Baru',
                        'message' => 'Warga ' . $user->name . ' menukar reward "' . $reward->name . '". Segera proses.',
                        'type' => 'info',
                        'link' => route('mitra.rewards.index'),
                        'is_read' => false,
                    ]);
                } catch (\Exception $e) {}
            }

            \DB::commit();

            return redirect()
                ->route('user.rewards.index')
                ->with('success', 'Reward berhasil ditukar! Poin kamu berkurang ' . number_format($reward->point_required) . '.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal tukar reward: ' . $e->getMessage());
        }
    }

    // ============================================================
    // MITRA — Kelola Penukaran Reward
    // ============================================================

    public function mitraRedemptions(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = \App\Models\RewardRedemption::with('user', 'reward')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $redemptions = $query->paginate(20)->withQueryString();

        $stats = [
            'pending' => \App\Models\RewardRedemption::where('status', 'pending')->count(),
            'processed' => \App\Models\RewardRedemption::where('status', 'processed')->count(),
            'completed' => \App\Models\RewardRedemption::where('status', 'completed')->count(),
        ];

        return view('mitra.rewards.redemptions', compact('redemptions', 'stats', 'status'));
    }

    public function mitraUpdateRedemption(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,completed',
        ]);

        $redemption = \App\Models\RewardRedemption::findOrFail($id);

        $data = ['status' => $request->status];

        if ($request->status === 'completed') {
            $data['processed_at'] = now();
        }

        $redemption->update($data);

        try {
            \App\Models\Notification::create([
                'user_id' => $redemption->user_id,
                'title' => 'Penukaran Reward ' . ucfirst($request->status),
                'message' => 'Penukaran reward "' . ($redemption->reward->name ?? '-') . '" sekarang berstatus: ' . $request->status,
                'type' => $request->status === 'completed' ? 'success' : 'info',
                'link' => route('user.rewards.index'),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Status penukaran berhasil diupdate.');
    }
}