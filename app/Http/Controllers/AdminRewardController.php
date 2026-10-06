<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminRewardController extends Controller
{
    public function index(Request $request)
    {
        $query = Reward::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rewards = $query->latest()->get();

        return view('admin.rewards.index', compact('rewards'));
    }

    public function show($id)
    {
        $reward = Reward::findOrFail($id);

        return view('admin.rewards.show', compact('reward'));
    }

    public function exportPdf(Request $request)
    {
        $query = Reward::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rewards = $query->latest()->get();

        $pdf = Pdf::loadView('admin.rewards.pdf', compact('rewards'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-reward-wastelyn-' . now()->format('Y-m-d') . '.pdf');
    }
}