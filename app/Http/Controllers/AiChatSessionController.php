<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use Illuminate\Http\Request;

class AiChatSessionController extends Controller
{
    public function index()
    {
        $sessions = AiChatSession::where('user_id', auth()->id())
            ->withCount('messages')
            ->latest('session_id')
            ->paginate(10);

        return view('user.ai-chat-sessions.index', compact('sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:100',
        ]);

        $session = AiChatSession::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'] ?? 'Chat ' . now()->format('d/m/Y H:i'),
        ]);

        return redirect()
            ->route('user.ai-chat-sessions.show', $session->session_id)
            ->with('success', 'Chat session dimulai.');
    }

    public function show($id)
    {
        $session = $this->findSession($id)->load('messages');

        $sessions = AiChatSession::where('user_id', auth()->id())
            ->latest('session_id')
            ->limit(30)
            ->get();

        return view('user.ai-chat-sessions.show', compact('session', 'sessions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
        ]);

        $session = $this->findSession($id);
        $session->update(['title' => $validated['title']]);

        return redirect()->back()->with('success', 'Judul session diperbarui.');
    }

    public function destroy($id)
    {
        $session = $this->findSession($id);

        $session->messages()->delete();
        $session->delete();

        return redirect()
            ->route('user.ai-chat-sessions.index')
            ->with('success', 'Session dihapus.');
    }

    private function findSession($id): AiChatSession
    {
        return AiChatSession::where('user_id', auth()->id())
            ->where('session_id', $id)
            ->firstOrFail();
    }
}