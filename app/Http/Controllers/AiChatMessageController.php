<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AiChatMessageController extends Controller
{
    public function __construct(protected GeminiService $gemini) {}

    public function store(Request $request, $sessionId)
    {
        $session = AiChatSession::where('user_id', auth()->id())->findOrFail($sessionId);

        $request->validate([
            'message' => 'required|string|max:4000',
        ]);

        $userMessage = $request->input('message');

        AiChatMessage::create([
            'session_id' => $session->session_id,
            'sender'     => 'user',
            'message'    => $userMessage,
        ]);

        $history = $session->messages()
            ->orderBy('message_id')
            ->get()
            ->map(fn ($m) => [
                'role'  => $m->sender === 'ai' ? 'model' : 'user',
                'parts' => [['text' => $m->message]],
            ])
            ->toArray();

        array_pop($history);

        try {
            $reply = $this->gemini->chat(
                message: $userMessage,
                history: $history,
                systemPrompt: 'Anda adalah asisten AI yang ramah dan membantu.'
            );
        } catch (\Throwable $e) {
            return redirect()
                ->route('user.ai-chat-sessions.show', $session->session_id)
                ->with('error', 'Gagal menghubungi AI: ' . $e->getMessage());
        }

        AiChatMessage::create([
            'session_id' => $session->session_id,
            'sender'     => 'ai',
            'message'    => $reply,
        ]);

        if ($session->messages()->count() === 2 && str_starts_with($session->title, 'Chat ')) {
            $session->update(['title' => mb_substr($userMessage, 0, 50)]);
        }

        return redirect()
            ->route('user.ai-chat-sessions.show', $session->session_id)
            ->with('success', 'Pesan terkirim.');
    }

    public function destroy($sessionId, $messageId)
    {
        $session = AiChatSession::where('user_id', auth()->id())->findOrFail($sessionId);
        $message = AiChatMessage::where('session_id', $session->session_id)->findOrFail($messageId);

        $message->delete();

        return redirect()
            ->route('user.ai-chat-sessions.show', $session->session_id)
            ->with('success', 'Pesan dihapus.');
    }
}