<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MissionController extends Controller
{

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
        $validated = $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('missions', 'public');
        }

        Mission::create($validated);

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

        $validated = $request->validate([
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        if ($request->hasFile('image')) {
            if ($mission->image) {
                Storage::disk('public')->delete($mission->image);
            }
            $validated['image'] = $request->file('image')->store('missions', 'public');
        }

        $mission->update($validated);

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil diupdate');
    }

    public function mitraDestroy(Mission $mission)
    {
        $this->authorizeMission($mission);

        if ($mission->image) {
            Storage::disk('public')->delete($mission->image);
        }

        $mission->delete();

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil dihapus');
    } 
    private function getMyBankIds(): array
    {
        return WasteBank::where('mitra_id', auth()->id())
            ->pluck('bank_id')
            ->toArray();
    }

    private function authorizeMission(Mission $mission): void
    {
        abort_if(!in_array($mission->bank_id, $this->getMyBankIds()), 403, 'Misi bukan milik kamu.');
    }
}