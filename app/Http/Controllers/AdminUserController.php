<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar user + search.
     */
    public function index(Request $request)
    {
        $users = $this->getFilteredUsers($request)
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Export data user ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $users = $this->getFilteredUsers($request)->get();

        $pdf = Pdf::loadView('admin.users.pdf', compact('users'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-user-wastelyn.pdf');
    }

    /**
     * Query user dengan filter pencarian.
     */
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

    /**
     * Form tambah user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru.
     */
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

        // Status otomatis berdasarkan role
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

        // Upload foto
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('users', 'public');
        }

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Detail user.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Form edit user.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
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
        ]);

        $data = $request->only([
            'name',
            'email',
            'phone',
            'address',
            'role',
        ]);

        // Jika role berubah menjadi mitra
        if (
            $request->role === 'mitra' &&
            $user->role !== 'mitra'
        ) {
            $data['status'] = 'pending';
        }

        // Jika mitra diubah menjadi warga atau admin
        if (
            $request->role !== 'mitra' &&
            $user->role === 'mitra'
        ) {
            $data['status'] = 'active';
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload foto baru
        if ($request->hasFile('photo')) {

            // Hapus foto lama
            if (
                $user->photo &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $data['photo'] = $request->file('photo')
                ->store('users', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Setujui Mitra / Bank Sampah.
     */
    public function approve(string $id)
    {
        $user = User::findOrFail($id);

        // Hanya mitra yang bisa disetujui
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

    /**
     * Tolak Mitra / Bank Sampah.
     */
    public function reject(string $id)
    {
        $user = User::findOrFail($id);

        // Hanya mitra yang bisa ditolak
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

    /**
     * Hapus user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Tidak boleh menghapus akun sendiri
        if ($user->user_id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Hapus foto dari storage
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