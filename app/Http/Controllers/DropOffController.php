<?php

namespace App\Http\Controllers;

use App\Models\PickupRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DropOffController extends Controller
{
    public function arrive($id)
    {
        $pickup = PickupRequest::where('pickup_request_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pickup) {
            abort(404, 'Setoran tidak ditemukan.');
        }

        if ($pickup->pickup_method !== 'dropoff') {
            return back()->with('error', 'Ini bukan setoran dropoff.');
        }

        if ($pickup->status !== PickupRequest::STATUS_ACCEPTED) {
            return back()->with('error', 'Status setoran belum bisa dikonfirmasi. Status saat ini: ' . $pickup->status);
        }

        $pickup->update([
            'status' => PickupRequest::STATUS_WAITING_VERIFICATION,
            'arrived_at' => now(),
        ]);

        try {
            Notification::create([
                'user_id' => $pickup->mitra_id,
                'title' => 'Warga Sudah Tiba',
                'message' => "Warga {$pickup->user->name} sudah tiba di bank sampah untuk setoran {$pickup->wasteCategory->name}.",
                'type' => 'info',
                'link' => route('mitra.pickup-requests.show', $pickup->pickup_request_id),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
        }

        return back()->with('success', 'Terima kasih! Mitra akan segera memverifikasi setoranmu.');
    }
}