<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = $this->getFilteredUsers($request)
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function exportPdf(Request $request)
    {
        $users = $this->getFilteredUsers($request)->get();

        $pdf = Pdf::loadView('admin.users.pdf', compact('users'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-user-wastelyn.pdf');
    }

    private function getFilteredUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('user_id', 'desc');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'role' => 'required|in:warga,mitra,admin',
        ]);

        $status = $request->role === 'mitra'
            ? 'pending'
            : 'active';

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'status' => $status,
            'xp' => 0,
            'points' => 0,
            'level' => 1,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('users', 'public');
        }

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' .
                $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'role' => 'required|in:warga,mitra,admin',
            'status' => 'required|in:active,pending,rejected,inactive',
        ]);

        $data = $request->only([
            'name',
            'email',
            'phone',
            'address',
            'role',
            'status',
        ]);

        if (
            $request->role === 'mitra' &&
            $user->role !== 'mitra'
        ) {
            $data['status'] = 'pending';
        }

        if (
            $request->role !== 'mitra' &&
            $user->role === 'mitra'
        ) {
            $data['status'] = 'active';
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if (
                $user->photo &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $data['photo'] = $request->file('photo')
                ->store('users', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function approve(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'mitra') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Hanya akun mitra yang dapat disetujui.');
        }

        $user->update([
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Mitra Bank Sampah berhasil disetujui.');
    }

    public function reject(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'mitra') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Hanya akun mitra yang dapat ditolak.');
        }

        $user->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Mitra Bank Sampah ditolak.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->user_id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        if (
            $user->photo &&
            Storage::disk('public')->exists($user->photo)
        ) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}