<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::with('user')->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notification::create($request->all());
        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dikirim');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        $notification->load('user');
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        $users = User::all();
        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'is_read' => 'nullable|boolean',
        ]);

        $notification->update($request->all());
        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Broadcast notification to all users.
     */
    public function broadcast(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $users = User::all();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->user_id,
                'title' => $request->title,
                'message' => $request->message,
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi broadcast berhasil dikirim ke semua user');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $notification = Notification::findOrFail($notification);
        $notification->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }
}
