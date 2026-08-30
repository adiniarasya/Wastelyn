<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use Illuminate\Http\Request;

class AiChatMessageController extends Controller
{
    public function index($sessionId)
    {
        $messages = AiChatMessage::where('session_id', $sessionId)
            ->whereHas('session', fn($q) => $q->where('user_id', auth()->id()))
            ->get();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:ai_chat_sessions,id',
            'message' => 'required|string',
            'sender' => 'required|in:user,ai',
        ]);

        $session = AiChatSession::where('user_id', auth()->id())->findOrFail($request->session_id);

        $message = AiChatMessage::create([
            'session_id' => $session->id,
            'message' => $request->message,
            'sender' => $request->sender,
        ]);

        return response()->json($message);
    }

    public function show($id)
    {
        $message = AiChatMessage::whereHas('session', fn($q) => $q->where('user_id', auth()->id()))->findOrFail($id);
        return response()->json($message);
    }

    public function destroy($id)
    {
        $message = AiChatMessage::whereHas('session', fn($q) => $q->where('user_id', auth()->id()))->findOrFail($id);
        $message->delete();
        return response()->json(['success' => true]);
    }
}