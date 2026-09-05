<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickupRequest;
use App\Models\Transaction;
use App\Models\WasteBank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MitraController extends Controller
{
    /**
     * Dashboard Mitra
     */
    public function dashboard()
    {
        try {
            $mitraId = auth()->id();

            // Ambil semua pickup request milik mitra ini
            // Menggunakan mitra_id sesuai model
            $totalPickups = PickupRequest::where('mitra_id', $mitraId)->count();
            
            $totalBerat = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->sum('berat_aktual') ?? 0;
                
            $totalPendapatan = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->sum('total_harga') ?? 0;
                
            $totalNasabah = PickupRequest::where('mitra_id', $mitraId)
                ->distinct('user_id')
                ->count('user_id');

            // Permintaan Masuk (pending)
            $pendingPickups = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'pending')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Riwayat Setoran Terbaru
            $recentPickups = PickupRequest::where('mitra_id', $mitraId)
                ->whereIn('status', ['accepted', 'completed'])
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Grafik 7 Hari
            $chartLabels = [];
            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = now()->subDays($i)->format('d M');
                $chartData[] = PickupRequest::where('mitra_id', $mitraId)
                    ->where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('berat_aktual') ?? 0;
            }

            return view('mitra.dashboard', compact(
                'totalPickups',
                'totalBerat',
                'totalPendapatan',
                'totalNasabah',
                'pendingPickups',
                'recentPickups',
                'chartLabels',
                'chartData'
            ));

        } catch (\Exception $e) {
            return view('mitra.dashboard', [
                'totalPickups' => 0,
                'totalBerat' => 0,
                'totalPendapatan' => 0,
                'totalNasabah' => 0,
                'pendingPickups' => collect(),
                'recentPickups' => collect(),
                'chartLabels' => [],
                'chartData' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Halaman Kelola Setoran
     */
    public function kelolaSetoran()
    {
        try {
            $mitraId = auth()->id();

            $pickups = PickupRequest::where('mitra_id', $mitraId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('mitra.kelola-setoran', compact('pickups'));

        } catch (\Exception $e) {
            return view('mitra.kelola-setoran', ['pickups' => collect(), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Detail Pickup Request
     */
    public function detailSetoran($id)
    {
        try {
            $mitraId = auth()->id();
            
            $pickup = PickupRequest::where('pickup_request_id', $id)
                ->where('mitra_id', $mitraId)
                ->with('user')
                ->firstOrFail();

            return view('mitra.detail-setoran', compact('pickup'));

        } catch (\Exception $e) {
            return redirect()->route('mitra.kelola-setoran')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Update Status Pickup Request
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,accepted,completed,cancelled',
                'berat_aktual' => 'nullable|numeric|min:0',
                'total_harga' => 'nullable|numeric|min:0',
            ]);

            $mitraId = auth()->id();
            
            $pickup = PickupRequest::where('pickup_request_id', $id)
                ->where('mitra_id', $mitraId)
                ->firstOrFail();

            $pickup->status = $request->status;

            if ($request->status == 'completed') {
                $pickup->berat_aktual = $request->berat_aktual ?? 0;
                $pickup->total_harga = $request->total_harga ?? 0;

                // Tambah XP dan Poin ke User
                $user = User::where('user_id', $pickup->user_id)->first();
                if ($user) {
                    $xpEarned = ($request->berat_aktual ?? 0) * 20; // 20 XP per kg
                    $pointsEarned = ($request->berat_aktual ?? 0) * 10; // 10 Poin per kg

                    $user->xp = ($user->xp ?? 0) + $xpEarned;
                    $user->points = ($user->points ?? 0) + $pointsEarned;
                    $user->save();

                    // Catat transaksi
                    try {
                        Transaction::create([
                            'user_id' => $pickup->user_id,
                            'mitra_id' => $mitraId,
                            'pickup_request_id' => $pickup->pickup_request_id,
                            'type' => 'earn',
                            'points' => $pointsEarned,
                            'description' => "Setoran sampah {$request->berat_aktual}kg",
                        ]);
                    } catch (\Exception $e) {
                        // Skip jika tabel transactions belum ada
                    }
                }
            }

            $pickup->save();

            return redirect()->back()->with('success', 'Status setoran berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Riwayat Setoran
     */
    public function riwayatSetoran()
    {
        try {
            $mitraId = auth()->id();

            $riwayat = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('mitra.riwayat-setoran', compact('riwayat'));

        } catch (\Exception $e) {
            return view('mitra.riwayat-setoran', ['riwayat' => collect(), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Halaman Statistik
     */
    public function statistics()
    {
        try {
            $mitraId = auth()->id();

            // Statistik per Jenis Sampah
            $sampahPerJenis = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->select('jenis_sampah', DB::raw('SUM(berat_aktual) as total_berat'), DB::raw('COUNT(*) as total_transaksi'))
                ->groupBy('jenis_sampah')
                ->get();

            // Grafik 12 Bulan Terakhir
            $bulanLabels = [];
            $bulanData = [];
            for ($i = 11; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $bulanLabels[] = $bulan->format('M Y');
                $bulanData[] = PickupRequest::where('mitra_id', $mitraId)
                    ->where('status', 'completed')
                    ->whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->sum('berat_aktual') ?? 0;
            }

            // Statistik Status
            $statusStats = [
                'pending' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'pending')->count(),
                'accepted' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'accepted')->count(),
                'completed' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'completed')->count(),
                'cancelled' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'cancelled')->count(),
            ];

            return view('mitra.statistics', compact('sampahPerJenis', 'bulanLabels', 'bulanData', 'statusStats'));

        } catch (\Exception $e) {
            return view('mitra.statistics', [
                'sampahPerJenis' => collect(),
                'bulanLabels' => [],
                'bulanData' => [],
                'statusStats' => ['pending' => 0, 'accepted' => 0, 'completed' => 0, 'cancelled' => 0],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Halaman Profil Mitra
     */
    public function profile()
    {
        try {
            $mitra = auth()->user();
            return view('mitra.profile', compact('mitra'));

        } catch (\Exception $e) {
            return view('mitra.profile', ['mitra' => auth()->user()]);
        }
    }

    /**
     * Update Profil Mitra
     */
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
                'no_telepon' => 'nullable|string|max:15',
                'alamat' => 'nullable|string',
            ]);

            $user = auth()->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->alamat = $request->alamat;
            $user->save();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}