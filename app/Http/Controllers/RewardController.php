<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{

    public function index()
    {
        $rewards = Reward::all();
        return view('admin.rewards.index', compact('rewards'));
    }

    public function create()
    {
        return view('admin.rewards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
        ]);

        Reward::create($request->all());

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil ditambahkan');
    }

    public function show(Reward $reward)
    {
        return view('admin.rewards.show', compact('reward'));
    }

    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
        ]);

        $reward->update($request->all());

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil diupdate');
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil dihapus');
    }


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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
        ]);

        Reward::create($request->all());

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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
        ]);

        $reward->update($request->all());

        return redirect()
            ->route('mitra.rewards.index')
            ->with('success', 'Reward berhasil diupdate');
    }

    public function mitraDestroy(Reward $reward)
    {
        $reward->delete();

        return redirect()
            ->route('mitra.rewards.index')
            ->with('success', 'Reward berhasil dihapus');
    }
}