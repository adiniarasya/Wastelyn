<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use Illuminate\Http\Request;

class AiChatSessionController extends Controller
{
    public function index()
    {
        $sessions = AiChatSession::where('user_id', auth()->id())->latest()->paginate(10);
        return view('user.ai-chat-sessions.index', compact('sessions'));
    }

    public function store(Request $request)
    {
        $session = AiChatSession::create([
            'user_id' => auth()->id(),
            'title' => $request->title ?? 'Chat ' . now()->format('d/m/Y H:i'),
        ]);

        return redirect()->route('user.ai-chat-sessions.show', $session->id)->with('success', 'Chat session dimulai.');
    }

    public function show($id)
    {
        $session = AiChatSession::where('user_id', auth()->id())->with('messages')->findOrFail($id);
        return view('user.ai-chat-sessions.show', compact('session'));
    }

    public function update(Request $request, $id)
    {
        $session = AiChatSession::where('user_id', auth()->id())->findOrFail($id);
        $session->update(['title' => $request->title]);
        return redirect()->back()->with('success', 'Judul session diperbarui.');
    }

    public function destroy($id)
    {
        $session = AiChatSession::where('user_id', auth()->id())->findOrFail($id);
        $session->delete();
        return redirect()->route('user.ai-chat-sessions.index')->with('success', 'Session dihapus.');
    }
}