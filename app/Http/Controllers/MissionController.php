<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\WasteBank;
use Illuminate\Http\Request;

class MissionController extends Controller
{

    public function index()
    {
        $missions = Mission::with('bank')->get();
        return view('admin.missions.index', compact('missions'));
    }

    public function create()
    {
        $wasteBanks = WasteBank::all();
        return view('admin.missions.create', compact('wasteBanks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'type' => 'required|in:quantitative,qualitative',
            'ai_prompt' => 'required|string',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Mission::create($request->all());

        return redirect()
            ->route('admin.missions.index')
            ->with('success', 'Misi berhasil ditambahkan');
    }

    public function show(Mission $mission)
    {
        $mission->load('bank', 'userMissions.user');
        return view('admin.missions.show', compact('mission'));
    }

    public function edit(Mission $mission)
    {
        $wasteBanks = WasteBank::all();
        return view('admin.missions.edit', compact('mission', 'wasteBanks'));
    }

    public function update(Request $request, Mission $mission)
    {
        $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'type' => 'required|in:quantitative,qualitative',
            'ai_prompt' => 'required|string',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $mission->update($request->all());

        return redirect()
            ->route('admin.missions.index')
            ->with('success', 'Misi berhasil diupdate');
    }

    public function destroy(Mission $mission)
    {
        $mission->delete();

        return redirect()
            ->route('admin.missions.index')
            ->with('success', 'Misi berhasil dihapus');
    }

    // ============================================================
    // METHOD MITRA — buat mitra (dengan filter bank_id miliknya)
    // ============================================================

    public function mitraIndex()
    {
        $bankIds = $this->getMyBankIds();

        $missions = Mission::whereIn('bank_id', $bankIds)
            ->with('bank')
            ->latest()
            ->get();

        return view('mitra.missions.index', compact('missions'));
    }

    public function mitraCreate()
    {
        $wasteBanks = WasteBank::whereIn('bank_id', $this->getMyBankIds())->get();
        return view('mitra.missions.create', compact('wasteBanks'));
    }

    public function mitraStore(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'type' => 'required|in:quantitative,qualitative',
            'ai_prompt' => 'required|string',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        abort_if(!in_array($request->bank_id, $this->getMyBankIds()), 403, 'Bank sampah bukan milik kamu.');

        Mission::create($request->all());

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil ditambahkan');
    }

    public function mitraShow(Mission $mission)
    {
        $this->authorizeMission($mission);

        $mission->load('bank', 'userMissions.user');

        return view('mitra.missions.show', compact('mission'));
    }

    public function mitraEdit(Mission $mission)
    {
        $this->authorizeMission($mission);

        $wasteBanks = WasteBank::whereIn('bank_id', $this->getMyBankIds())->get();

        return view('mitra.missions.edit', compact('mission', 'wasteBanks'));
    }

    public function mitraUpdate(Request $request, Mission $mission)
    {
        $this->authorizeMission($mission);

        $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'type' => 'required|in:quantitative,qualitative',
            'ai_prompt' => 'required|string',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        abort_if(!in_array($request->bank_id, $this->getMyBankIds()), 403, 'Bank sampah bukan milik kamu.');

        $mission->update($request->all());

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil diupdate');
    }

    public function mitraDestroy(Mission $mission)
    {
        $this->authorizeMission($mission);

        $mission->delete();

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil dihapus');
    }

    // ============================================================
    // HELPER
    // ============================================================

    /**
     * Ambil semua bank_id milik mitra yang login.
     */
    private function getMyBankIds(): array
    {
        return WasteBank::where('mitra_id', auth()->id())
            ->pluck('bank_id')
            ->toArray();
    }

    /**
     * Pastikan misi milik mitra yang login.
     */
    private function authorizeMission(Mission $mission): void
    {
        abort_if(!in_array($mission->bank_id, $this->getMyBankIds()), 403, 'Misi bukan milik kamu.');
    }
}