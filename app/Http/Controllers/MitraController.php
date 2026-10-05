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
            $pendapatanBulanIni = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('total_harga') ?? 0;

            $pendapatanBulanLalu = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->whereYear('created_at', now()->subMonth()->year)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->sum('total_harga') ?? 0;

            $persenPendapatan = $pendapatanBulanLalu > 0
                ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100
                : ($pendapatanBulanIni > 0 ? 100 : 0);

            $setoranBulanIni = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();

            $setoranBulanLalu = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->whereYear('created_at', now()->subMonth()->year)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->count();

            $persenSetoran = $setoranBulanLalu > 0
                ? (($setoranBulanIni - $setoranBulanLalu) / $setoranBulanLalu) * 100
                : ($setoranBulanIni > 0 ? 100 : 0);
            $pendapatanLabels = [];
            $pendapatanData = [];
            for ($i = 11; $i >= 0; $i--) {
                $bln = now()->subMonths($i);
                $pendapatanLabels[] = $bln->format('M Y');
                $pendapatanData[] = PickupRequest::where('mitra_id', $mitraId)
                    ->where('status', 'completed')
                    ->whereYear('created_at', $bln->year)
                    ->whereMonth('created_at', $bln->month)
                    ->sum('total_harga') ?? 0;
            }

            $pendingPickups = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'pending')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $recentPickups = PickupRequest::where('mitra_id', $mitraId)
                ->whereIn('status', ['accepted', 'completed'])
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

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
                'chartData',
                'pendapatanBulanIni',
                'pendapatanBulanLalu',
                'persenPendapatan',
                'setoranBulanIni',
                'setoranBulanLalu',
                'persenSetoran',
                'pendapatanLabels',
                'pendapatanData'
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
                'status' => 'required|in:pending,accepted,scheduled,completed,rejected',
                'berat_aktual' => 'nullable|numeric|min:0',
                'total_harga' => 'nullable|numeric|min:0',
            ]);

            $mitraId = auth()->id();

            $pickup = PickupRequest::where('pickup_request_id', $id)
                ->where('mitra_id', $mitraId)
                ->firstOrFail();

            $oldStatus = $pickup->status;
            $pickup->status = $request->status;

            if ($request->status == 'completed') {
                $pickup->berat_aktual = $request->berat_aktual ?? 0;
                $pickup->total_harga = $request->total_harga ?? 0;

                // Kasih reward HANYA kalau transisi pertama kali ke 'completed'
                if ($oldStatus !== 'completed' && $pickup->user_mission_id) {
                    $userMission = \App\Models\UserMission::find($pickup->user_mission_id);

                    if ($userMission && $userMission->status !== 'completed') {
                        // Update status misi warga
                        $userMission->update([
                            'status' => 'completed',
                            'picked_up_at' => now(),
                            'completed_at' => now(),
                        ]);

                        // Kasih reward ke warga
                        $mission = $userMission->mission;
                        $user = $userMission->user;

                        if ($mission && $user) {
                            $user->addXp($mission->reward_xp);
                            $user->addPoints($mission->reward_points);

                            try {
                                Transaction::create([
                                    'user_id' => $user->user_id,
                                    'mitra_id' => $mitraId,
                                    'pickup_request_id' => $pickup->pickup_request_id,
                                    'type' => 'earn',
                                    'points' => $mission->reward_points,
                                    'description' => "Misi selesai: {$mission->title}",
                                ]);
                            } catch (\Exception $e) {
                                // skip kalau tabel transactions bermasalah
                            }
                        }
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
    public function statistics(Request $request)
    {
        try {
            $mitraId = auth()->id();

            // Filter periode: 'all' atau format YYYY-MM
            $periode = $request->input('periode', 'all');
            $bulan = null;
            $tahun = null;

            if ($periode !== 'all' && preg_match('/^\d{4}-\d{2}$/', $periode)) {
                [$tahun, $bulan] = explode('-', $periode);
            }

            // Base query: transaksi completed milik mitra ini
            $baseQuery = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed');

            if ($bulan && $tahun) {
                $baseQuery->whereYear('created_at', $tahun)
                          ->whereMonth('created_at', $bulan);
            }

            // ===== STATISTIK PER JENIS SAMPAH =====
            $sampahPerJenis = (clone $baseQuery)
                ->select(
                    'waste_category_id',
                    DB::raw('SUM(berat_aktual) as total_berat'),
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(points_earned) as total_poin'),
                    DB::raw('SUM(total_harga) as total_pendapatan')
                )
                ->groupBy('waste_category_id')
                ->with('wasteCategory')
                ->get();

            // Total keseluruhan
            $totalSemuaBerat = $sampahPerJenis->sum('total_berat');
            $totalSemuaTransaksi = $sampahPerJenis->sum('total_transaksi');
            $totalSemuaPoin = $sampahPerJenis->sum('total_poin');
            $totalSemuaPendapatan = $sampahPerJenis->sum('total_pendapatan');

            // ===== GRAFIK 12 BULAN TERAKHIR =====
            $sampahPerJenis = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->select('jenis_sampah', DB::raw('SUM(berat_aktual) as total_berat'), DB::raw('COUNT(*) as total_transaksi'))
                ->groupBy('jenis_sampah')
                ->get();

            $bulanLabels = [];
            $bulanData = [];
            for ($i = 11; $i >= 0; $i--) {
                $bln = now()->subMonths($i);
                $bulanLabels[] = $bln->format('M Y');
                $bulanData[] = PickupRequest::where('mitra_id', $mitraId)
                    ->where('status', 'completed')
                    ->whereYear('created_at', $bln->year)
                    ->whereMonth('created_at', $bln->month)
                    ->sum('berat_aktual') ?? 0;
            }

            // ===== STATISTIK STATUS =====
            $statusStats = [
                'pending' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'pending')->count(),
                'accepted' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'accepted')->count(),
                'completed' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'completed')->count(),
                'rejected' => PickupRequest::where('mitra_id', $mitraId)->where('status', 'rejected')->count(),
            ];

            // List periode untuk dropdown (12 bulan terakhir)
            $listPeriode = [];
            for ($i = 11; $i >= 0; $i--) {
                $bln = now()->subMonths($i);
                $listPeriode[$bln->format('Y-m')] = $bln->format('F Y');
            }

            return view('mitra.statistics', compact(
                'sampahPerJenis',
                'totalSemuaBerat',
                'totalSemuaTransaksi',
                'totalSemuaPoin',
                'totalSemuaPendapatan',
                'bulanLabels',
                'bulanData',
                'statusStats',
                'listPeriode',
                'periode'
            ));

        } catch (\Exception $e) {
            return view('mitra.statistics', [
                'sampahPerJenis' => collect(),
                'totalSemuaBerat' => 0,
                'totalSemuaTransaksi' => 0,
                'totalSemuaPoin' => 0,
                'totalSemuaPendapatan' => 0,
                'bulanLabels' => [],
                'bulanData' => [],
                'statusStats' => ['pending' => 0, 'accepted' => 0, 'completed' => 0, 'rejected' => 0],
                'listPeriode' => [],
                'periode' => 'all',
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
                'email' => 'required|email|max:255|unique:users,email,' . auth()->id() . ',user_id',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
            ]);

            /** @var \App\Models\User $user */
            $user = auth()->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
        /**
     * Laporan Bulanan Mitra
     */
    public function laporan(Request $request)
    {
        $mitraId = auth()->id();

        // Default: bulan & tahun sekarang
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Base query: setoran selesai milik mitra ini di bulan & tahun itu
        $baseQuery = PickupRequest::where('mitra_id', $mitraId)
            ->where('status', 'completed')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan);

        // Ringkasan
        $totalTransaksi = (clone $baseQuery)->count();
        $totalBerat = (clone $baseQuery)->sum('berat_aktual') ?? 0;
        $totalPoin = (clone $baseQuery)->sum('points_earned') ?? 0;
        $totalPendapatan = (clone $baseQuery)->sum('total_harga') ?? 0;
        $totalXp = (clone $baseQuery)->sum('xp_earned') ?? 0;

        // Grafik: setoran per hari dalam bulan itu
        $jumlahHari = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $chartLabels[] = $i;
            $chartData[] = PickupRequest::where('mitra_id', $mitraId)
                ->where('status', 'completed')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->whereDay('created_at', $i)
                ->sum('berat_aktual') ?? 0;
        }

        // Detail transaksi
        $transaksi = (clone $baseQuery)
            ->with('user', 'wasteCategory')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $listBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $listTahun = range(now()->year - 3, now()->year);

        return view('mitra.laporan.index', compact(
            'bulan', 'tahun',
            'listBulan', 'listTahun',
            'totalTransaksi', 'totalBerat', 'totalPoin', 'totalPendapatan', 'totalXp',
            'chartLabels', 'chartData',
            'transaksi'
        ));
    }
}