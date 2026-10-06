<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\WasteBank;
use Illuminate\Http\Request;

class AdminMissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Mission::with('bank')
            ->withCount('userMissions')
            ->latest();

        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $missions = $query->get();
        $wasteBanks = WasteBank::orderBy('name')->get();

        return view('admin.missions.index', compact('missions', 'wasteBanks'));
    }

    public function show(Mission $mission)
    {
        $mission->load('bank', 'userMissions.user');

        return view('admin.missions.show', compact('mission'));
    }

    public function exportPdf(Request $request)
    {
        $query = Mission::with('bank')->withCount('userMissions')->latest();

        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $missions = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.missions.pdf', compact('missions'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-misi-wastelyn-' . now()->format('Y-m-d') . '.pdf');
    }
}