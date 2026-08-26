<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\User;
use Illuminate\Http\Request;

class AiChatSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = AiChatSession::with('user')->get();
        return view('admin.ai-chat-sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.ai-chat-sessions.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
        ]);

        AiChatSession::create($request->all());
        return redirect()->route('admin.ai-chat-sessions.index')->with('success', 'Session berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(AiChatSession $aiChatSession)
    {
        $aiChatSession->load('user', 'messages');
        return view('admin.ai-chat-sessions.show', compact('aiChatSession'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiChatSession $aiChatSession)
    {
        $users = User::all();
        return view('admin.ai-chat-sessions.edit', compact('aiChatSession', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AiChatSession $aiChatSession)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title' => 'required|string|max:255',
        ]);

        $aiChatSession->update($request->all());
        return redirect()->route('admin.ai-chat-sessions.index')->with('success', 'Session berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiChatSession $aiChatSession)
    {
        $aiChatSession->delete();
        return redirect()->route('admin.ai-chat-sessions.index')->with('success', 'Session berhasil dihapus');
    }
}
