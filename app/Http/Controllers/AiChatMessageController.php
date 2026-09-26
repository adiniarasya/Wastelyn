<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Services\BankSampahService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiChatMessageController extends Controller
{
    public function __construct(
        protected GeminiService $gemini,
        protected BankSampahService $bankSampah
    ) {
    }

    public function store(Request $request, $sessionId)
    {
        $session = AiChatSession::where('user_id', auth()->id())
            ->findOrFail($sessionId);

        $request->validate([
            'message' => 'nullable|string|max:4000',
            'image' => 'nullable|image|max:5120',
        ]);

        if (!$request->filled('message') && !$request->hasFile('image')) {
            return $this->respond($request, $session, [
                'error' => 'Pesan kosong',
            ], 422);
        }

        $pesanUser = $request->input('message') ?? '';

        $userMessage = AiChatMessage::create([
            'session_id' => $session->session_id,
            'sender' => 'user',
            'message' => $pesanUser ?: '[Gambar]',
        ]);

        $this->maybeAutoTitle($session, $pesanUser);

        $history = $session->messages()
            ->where('message_id', '<', $userMessage->message_id)
            ->orderBy('message_id')
            ->get()
            ->map(fn($m) => [
                'role' => $m->sender === 'bero' ? 'model' : 'user',
                'parts' => [['text' => $m->message]],
            ])
            ->toArray();

        $konteksTambahan = '';
        if ($pesanUser && preg_match('/bank\s*sampah|tempat sampah|buang sampah|daur ulang|setor sampah|wastebank|bank sampah terdekat/i', $pesanUser)) {
            $hasil = $this->bankSampah->cariRelevan($pesanUser, 5);
            $konteksTambahan = $this->bankSampah->formatUntukPrompt($hasil);
        }

        try {
            $reply = $this->gemini->chat(
                message: $pesanUser . $konteksTambahan,
                history: $history,
                systemPrompt: $this->beroSystemPrompt(),
                image: $request->file('image'),
            );
        } catch (\Throwable $e) {
            Log::error('Gemini error: ' . $e->getMessage());

            return $this->respond($request, $session, [
                'error' => 'Gagal menghubungi AI: ' . $e->getMessage(),
            ], 500);
        }

        $botMessage = AiChatMessage::create([
            'session_id' => $session->session_id,
            'sender' => 'bero',
            'message' => $reply,
        ]);

        return $this->respond($request, $session, [
            'user_message' => $userMessage,
            'bot_message' => $botMessage,
        ]);
    }

    public function destroy($sessionId, $messageId)
    {
        $session = AiChatSession::where('user_id', auth()->id())
            ->findOrFail($sessionId);

        $message = AiChatMessage::where('session_id', $session->session_id)
            ->findOrFail($messageId);

        $message->delete();

        return redirect()
            ->route('user.ai-chat-sessions.show', $session->session_id)
            ->with('success', 'Pesan dihapus.');
    }

    private function maybeAutoTitle(AiChatSession $session, ?string $firstMessage): void
    {
        if (!$firstMessage)
            return;
        if (!Str::startsWith($session->title, 'Chat '))
            return;

        $userCount = $session->messages()->where('sender', 'user')->count();
        if ($userCount !== 1)
            return;

        $session->update([
            'title' => mb_substr($firstMessage, 0, 50),
        ]);
    }

    private function respond(Request $request, AiChatSession $session, array $data, int $status = 200)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($data, $status);
        }

        $key = $status >= 400 ? 'error' : 'success';
        $flash = $data[$key]
            ?? ($data['bot_message']->message ?? 'OK');

        return redirect()
            ->route('user.ai-chat-sessions.show', $session->session_id)
            ->with($key, $flash);
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

    private function beroSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah "Bero", sahabat lingkungan yang asik, hangat, dan pintar soal sampah.

FOKUS TOPIK:
- Edukasi sampah (jenis, dampak, 3R, cara memilah)
- Tips & trik kelola sampah rumah tangga
- Bank sampah (cara setor, manfaat, lokasi terdekat)
- Misi harian ramah lingkungan

GAYA BICARA (WAJIB DIPATUHI):
- Ngobrol seperti teman, bukan seperti robot/AI.
- JANGAN pernah pakai frasa "Sebagai AI...", "Saya adalah asisten...", "Saya tidak punya akses...".
- Maksimal 1-2 emoji per pesan. Jangan berlebihan.
- Hindari bullet panjang. Kalau bisa, ngobrol natural 2-3 kalimat.
- Jangan mengulang pertanyaan user di awal jawaban.

ATURAN KHUSUS BANK SAMPAH:
- Kalau user tanya "bank sampah terdekat" atau sejenisnya, dan ada blok "DATA BANK SAMPAH" di bawah, LANGSUNG sebut 1-2 nama bank sampah + alamatnya dari data itu.
- Contoh gaya: "Di daerahmu ada nih, Bank Sampah Melati di Jl. Kebon Jeruk No. 12. Buka Senin-Sabtu jam 8 sampai 4 sore. Mau Bero jelaskan cara setornya?"
- Kalau tidak ada data yang cocok, jujur bilang belum ada datanya, lalu sarankan cek Google Maps atau tanya RT/RW. JANGAN mengarang.
- Jangan pernah bilang "saya tidak punya akses lokasi" — cukup pakai data yang diberikan.

MISI HARIAN:
- Kalau user minta misi, berikan 1 misi konkret & singkat yang bisa dilakukan hari ini (contoh: memilah plastik, timbang anorganik, bikin kompos dari sisa dapur).
- Buat misi terasa menyenangkan, bukan tugas berat.
PROMPT;
    }
}