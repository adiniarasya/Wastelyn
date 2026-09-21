<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PickupFlowController extends Controller
{
    public function take($id)
    {
        $pickup = PickupRequest::where('pickup_request_id', $id)
            ->whereNull('mitra_id')
            ->where('status', PickupRequest::STATUS_PENDING)
            ->firstOrFail();

        $pickup->update([
            'mitra_id' => Auth::id(),
            'status' => PickupRequest::STATUS_ACCEPTED,
        ]);

        $this->notify(
            $pickup->user_id,
            'Setoran Diambil Mitra',
            'Setoran sampah kamu sedang diproses oleh mitra.',
            'info',
            route('user.pickup-requests.show', $pickup->pickup_request_id)
        );

        return back()->with('success', 'Permintaan berhasil diambil. Silakan atur jadwal penjemputan.');
    }

    public function start(Request $request, $id)
    {
        $request->validate([
            'courier_name' => 'nullable|string|max:100',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
        ]);

        $pickup = PickupRequest::where('pickup_request_id', $id)
            ->where('mitra_id', Auth::id())
            ->whereIn('status', [PickupRequest::STATUS_ACCEPTED, PickupRequest::STATUS_SCHEDULED])
            ->firstOrFail();

        if (!$pickup->isPickup()) {
            return back()->with('error', 'Aksi ini hanya untuk metode pickup.');
        }

        $pickup->update([
            'courier_name' => $request->courier_name,
            'pickup_date' => $request->pickup_date ?? $pickup->pickup_date,
            'pickup_time' => $request->pickup_time ?? $pickup->pickup_time,
            'status' => PickupRequest::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        $this->notify(
            $pickup->user_id,
            'Kurir Sedang Menuju Lokasi',
            'Kurir mitra sedang dalam perjalanan untuk menjemput sampahmu.',
            'info',
            route('user.pickup-requests.show', $pickup->pickup_request_id)
        );

        return back()->with('success', 'Penjemputan dimulai. Kurir sedang menuju lokasi.');
    }

    public function receive($id)
    {
        $pickup = PickupRequest::where('pickup_request_id', $id)
            ->where('mitra_id', Auth::id())
            ->where('status', PickupRequest::STATUS_IN_PROGRESS)
            ->firstOrFail();

        $pickup->update([
            'status' => PickupRequest::STATUS_WAITING_VERIFICATION,
            'received_at' => now(),
        ]);

        return back()->with('success', 'Sampah diterima. Silakan verifikasi berat aktual.');
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'berat_aktual' => 'required|numeric|min:0.1',
            'price_per_kg' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pickup = PickupRequest::where('pickup_request_id', $id)
                ->where('mitra_id', Auth::id())
                ->where('status', PickupRequest::STATUS_WAITING_VERIFICATION)
                ->firstOrFail();

            $berat = (float) $request->berat_aktual;
            $kategori = $pickup->wasteCategory;

            $hargaEfektif = $pickup->harga_efektif;

            $pricePerKg = $request->price_per_kg
                ?? $hargaEfektif['price_per_kg'];

            $totalHarga = $berat * $pricePerKg;

            $xpEarned = (int) (20 * $berat);
            $pointPerKg = $kategori->point_per_kg ?? 10;
            $pointsEarned = (int) ($pointPerKg * $berat);
            $co2Saved = ($kategori->co2_saved_per_kg ?? 0.5) * $berat;

            $pickup->update([
                'berat_aktual' => $berat,
                'price_per_kg' => $pricePerKg,
                'total_harga' => $totalHarga,
                'xp_earned' => $xpEarned,
                'points_earned' => $pointsEarned,
                'co2_saved' => $co2Saved,
                'status' => PickupRequest::STATUS_COMPLETED,
                'verified_at' => now(),
            ]);

            $user = User::find($pickup->user_id);
            if ($user) {
                $user->xp = ($user->xp ?? 0) + $xpEarned;
                $user->points = ($user->points ?? 0) + $pointsEarned;
                $user->updateLevel();
                $user->save();
            }

            Transaction::create([
                'user_id' => $pickup->user_id,
                'pickup_request_id' => $pickup->pickup_request_id,
                'mitra_id' => Auth::id(),
                'type' => 'earn',
                'points' => $pointsEarned,
                'xp_earned' => $xpEarned,
                'description' => "Setoran {$kategori->name} {$berat}kg - diverifikasi mitra",
            ]);

            $this->notify(
                $pickup->user_id,
                'Setoran Selesai',
                "Setoran {$kategori->name} {$berat}kg berhasil diverifikasi. +{$xpEarned} XP, +{$pointsEarned} Poin.",
                'success',
                route('user.pickup-requests.show', $pickup->pickup_request_id)
            );

            DB::commit();

            return redirect()
                ->route('mitra.pickup-requests.index')
                ->with('success', "Setoran berhasil diverifikasi! +{$xpEarned} XP, +{$pointsEarned} Poin untuk warga.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

       public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $pickup = PickupRequest::where('pickup_request_id', $id)
            ->where(function ($q) {
                $q->whereNull('mitra_id')->orWhere('mitra_id', Auth::id());
            })
            ->whereIn('status', [PickupRequest::STATUS_PENDING, PickupRequest::STATUS_ACCEPTED])
            ->firstOrFail();

        $pickup->update([
            'status' => PickupRequest::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'mitra_id' => Auth::id(),
        ]);

        $this->notify(
            $pickup->user_id,
            'Setoran Ditolak',
            'Setoran sampah kamu ditolak oleh mitra. ' . ($request->rejection_reason ?? ''),
            'danger',
            route('user.pickup-requests.show', $pickup->pickup_request_id)
        );

        return back()->with('success', 'Permintaan ditolak.');
    }

    private function notify($userId, $title, $message, $type = 'info', $link = null)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'link' => $link,
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
        }
    }
}