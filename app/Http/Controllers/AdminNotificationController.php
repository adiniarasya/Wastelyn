<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'nullable|exists:users,user_id',
        ]);

        Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}