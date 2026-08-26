<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use Illuminate\Http\Request;

class AiChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = AiChatMessage::with('session')->get();
        return view('admin.ai-chat-messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sessions = AiChatSession::all();
        return view('admin.ai-chat-messages.create', compact('sessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:ai_chat_sessions,session_id',
            'role' => 'required|in:user,assistant,system',
            'content' => 'required|string',
        ]);

        AiChatMessage::create($request->all());
        return redirect()->route('admin.ai-chat-messages.index')->with('success', 'Pesan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(AiChatMessage $aiChatMessage)
    {
        return view('admin.ai-chat-messages.show', compact('aiChatMessage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiChatMessage $aiChatMessage)
    {
        $sessions = AiChatSession::all();
        return view('admin.ai-chat-messages.edit', compact('aiChatMessage', 'sessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AiChatMessage $aiChatMessage)
    {
        $request->validate([
            'session_id' => 'required|exists:ai_chat_sessions,session_id',
            'role' => 'required|in:user,assistant,system',
            'content' => 'required|string',
        ]);

        $aiChatMessage->update($request->all());
        return redirect()->route('admin.ai-chat-messages.index')->with('success', 'Pesan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiChatMessage $aiChatMessage)
    {
        $aiChatMessage->delete();
        return redirect()->route('admin.ai-chat-messages.index')->with('success', 'Pesan berhasil dihapus');
    }
}
