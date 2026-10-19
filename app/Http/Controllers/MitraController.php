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
