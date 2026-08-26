<?php

namespace App\Http\Controllers;

use App\Models\WasteBankStaff;
use App\Models\User;
use App\Models\WasteBank;
use Illuminate\Http\Request;

class WasteBankStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = WasteBankStaff::with('user', 'wasteBank')->get();
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'warga')->get();
        $wasteBanks = WasteBank::where('status', 'active')->get();
        return view('admin.staff.create', compact('users', 'wasteBanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:waste_bank_staff,user_id',
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'position' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        WasteBankStaff::create($request->all());
        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(WasteBankStaff $wasteBankStaff)
    {
        $wasteBankStaff->load('user', 'wasteBank');
        return view('admin.staff.show', compact('wasteBankStaff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WasteBankStaff $wasteBankStaff)
    {
        $users = User::where('role', 'warga')->get();
        $wasteBanks = WasteBank::where('status', 'active')->get();
        return view('admin.staff.edit', compact('wasteBankStaff', 'users', 'wasteBanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WasteBankStaff $wasteBankStaff)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:waste_bank_staff,user_id,' . $wasteBankStaff->staff_id . ',staff_id',
            'bank_id' => 'required|exists:waste_banks,bank_id',
            'position' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $wasteBankStaff->update($request->all());
        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WasteBankStaff $wasteBankStaff)
    {
        $wasteBankStaff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus');
    }

    /**
     * Toggle staff status (active/inactive).
     */
    public function toggleStatus(WasteBankStaff $wasteBankStaff)
    {
        $staff = WasteBankStaff::findOrFail($wasteBankStaff);
        $newStatus = $staff->status === 'active' ? 'inactive' : 'active';
        $staff->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status staff berhasil diubah menjadi ' . $newStatus);
    }
}
