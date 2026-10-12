<?php

namespace App\Http\Controllers;

use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Models\WasteBank;
use App\Models\Mission;
use App\Models\Reward;
use App\Models\WasteCategory;
use App\Services\BankSampahService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if ($pesanUser && preg_match('/bank\s*sampah|tempat sampah|buang sampah|daur ulang|setor sampah|wastebank|waste\s*bank/i', $pesanUser)) {
            $hasil = $this->bankSampah->cariRelevan($pesanUser, 5);
            $konteksTambahan = $this->bankSampah->formatUntukPrompt($hasil);
        }

        $dataKonteks = $this->siapkanDataKonteks($pesanUser, $request->hasFile('image'));

        try {
            $reply = $this->gemini->chat(
                message: $pesanUser . $konteksTambahan . $dataKonteks,
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

    public function show($id)
    {
        $session = $this->findSession($id)->load('messages');

        $sessions = AiChatSession::where('user_id', auth()->id())
            ->latest('session_id')
            ->limit(30)
            ->get();

        return view('user.ai-chat-sessions.show', compact('session', 'sessions'));
    }

    private function findSession($id)
    {
        return AiChatSession::where('user_id', auth()->id())
            ->where('session_id', $id)
            ->firstOrFail();
    }

    private function siapkanDataKonteks(string $pesanUser, bool $adaGambar = false): string
    {
        $user = auth()->user();
        if (!$user || !$user->user_id) {
            return '';
        }

        $userId = $user->user_id;

        $butuhMisi = preg_match('/misi|mission|tugas|tantangan|kegiatan/i', $pesanUser);
        $butuhReward = preg_match('/reward|hadiah|tukar|poin|point/i', $pesanUser);
        $butuhCo2 = preg_match('/co2|karbon|emisi|jejak|lingkungan|eco\s*habit|skor|score/i', $pesanUser);
        $butuhBank = preg_match('/bank\s*sampah|tempat sampah|setor sampah|wastebank|waste\s*bank/i', $pesanUser);
        $butuhKategori = $adaGambar || preg_match('/kategori|jenis\s*sampah|sampah\s*apa|ini\s*sampah|foto\s*sampah|gambar\s*sampah|harga\s*sampah|plastik|kertas|kaca|logam|organik|anorganik|b3|elektronik|kaleng|botol/i', $pesanUser);

        $konteks = "\n\n=== DATA WARGA (REAL DARI DATABASE) ===\n";
        $konteks .= "Nama: " . ($user->name ?? '-') . "\n";
        $konteks .= "Total CO2 Tersimpan: " . ($user->total_co2_saved ?? 0) . " kg\n";
        $konteks .= "Eco Habit Score: " . ($user->eco_habit_score ?? 0) . "\n";
        $konteks .= "Total Poin: " . ($user->total_point ?? 0) . "\n";

        if ($butuhKategori) {
            $kategori = WasteCategory::query()
                ->when(method_exists(WasteCategory::class, 'scopeAktif'), fn($q) => $q->aktif())
                ->limit(50)
                ->get()
                ->map(function ($k) {
                    return [
                        'nama' => $k->name ?? '-',
                        'deskripsi' => $k->description ?? '-',
                        'harga_per_kg' => $k->price_per_kg ?? 0,
                        'reward_per_kg' => $k->reward_per_kg ?? 0,
                        'point_per_kg' => $k->point_per_kg ?? 0,
                        'co2_saved_per_kg' => $k->co2_saved_per_kg ?? 0,
                        'icon' => $k->icon ?? '-',
                    ];
                })
                ->toArray();

            $konteks .= "\nDAFTAR KATEGORI SAMPAH (REAL DARI DATABASE):\n" . json_encode($kategori, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            $konteks .= "\nINSTRUKSI KHUSUS KATEGORI SAMPAH:\n";
            $konteks .= "- Jika user mengirim foto, identifikasi jenis sampah di foto, lalu cocokkan dengan daftar kategori di atas.\n";
            $konteks .= "- Sebutkan nama kategori, harga per kg, poin per kg, dan CO2 yang dihemat per kg.\n";
            $konteks .= "- Jika tidak yakin, tanyakan ke user jenis sampahnya.\n";
        }

        if ($butuhMisi || $butuhBank) {
            $misiTersedia = Mission::query()
                ->when(method_exists(Mission::class, 'scopeAktif'), fn($q) => $q->aktif())
                ->limit(10)
                ->get()
                ->map(function ($m) {
                    return [
                        'judul' => $m->title ?? '-',
                        'deskripsi' => $m->description ?? '-',
                        'target' => $m->target ?? 0,
                        'unit' => $m->unit ?? '-',
                        'reward_xp' => $m->reward_xp ?? 0,
                        'reward_points' => $m->reward_points ?? 0,
                        'tipe' => $m->type ?? '-',
                        'mulai' => $m->start_date ?? '-',
                        'selesai' => $m->end_date ?? '-',
                        'bank_id' => $m->bank_id ?? '-',
                        'ai_prompt' => $m->ai_prompt ?? '-',
                    ];
                })
                ->toArray();

            $konteks .= "\nDAFTAR MISI TERSEDIA:\n" . json_encode($misiTersedia, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

            $misiUser = $this->ambilMisiUser($userId);

            if (!empty($misiUser)) {
                $konteks .= "\nMISI YANG SEDANG DIAMBIL WARGA:\n" . json_encode($misiUser, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        }

        if ($butuhReward) {
            $rewardTersedia = Reward::query()
                ->when(method_exists(Reward::class, 'scopeAktif'), fn($q) => $q->aktif())
                ->limit(10)
                ->get()
                ->map(function ($r) {
                    return [
                        'nama' => $r->name ?? '-',
                        'deskripsi' => $r->description ?? '-',
                        'point_dibutuhkan' => $r->point_required ?? 0,
                        'stok' => $r->stock ?? 0,
                        'gambar' => $r->image ?? '-',
                        'status' => $r->status ?? '-',
                    ];
                })
                ->toArray();

            $konteks .= "\nDAFTAR REWARD TERSEDIA:\n" . json_encode($rewardTersedia, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        }

        if ($butuhCo2) {
            $riwayatCo2 = $this->ambilRiwayatMisi($userId);

            if (!empty($riwayatCo2)) {
                $konteks .= "\nRIWAYAT MISI SELESAI (untuk konteks CO2):\n" . json_encode($riwayatCo2, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        }

        if ($butuhBank) {
            $bankList = WasteBank::query()
                ->when(method_exists(WasteBank::class, 'scopeAktif'), fn($q) => $q->aktif())
                ->limit(5)
                ->get()
                ->map(function ($b) {
                    return [
                        'nama' => $b->name ?? '-',
                        'alamat' => $b->address ?? '-',
                        'telepon' => $b->phone ?? '-',
                        'email' => $b->email ?? '-',
                        'jam_buka' => $b->opening_hours ?? '-',
                        'latitude' => $b->latitude ?? '-',
                        'longitude' => $b->longitude ?? '-',
                        'mitra_id' => $b->mitra_id ?? '-',
                    ];
                })
                ->toArray();

            if (!empty($bankList)) {
                $konteks .= "\nDAFTAR BANK SAMPAH:\n" . json_encode($bankList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        }

        return $konteks;
    }

    private function kolomUserMissions(): array
    {
        static $kolom = null;
        if ($kolom === null) {
            $kolom = DB::getSchemaBuilder()->getColumnListing('user_missions');
        }
        return $kolom;
    }

    private function ambilMisiUser($userId): array
    {
        $kolom = $this->kolomUserMissions();

        $select = ['missions.title as judul', 'missions.target', 'missions.unit'];

        if (in_array('progress', $kolom)) {
            $select[] = 'user_missions.progress as progress';
        }
        if (in_array('unique_code', $kolom)) {
            $select[] = 'user_missions.unique_code';
        }
        if (in_array('started_at', $kolom)) {
            $select[] = 'user_missions.started_at';
        }
        if (in_array('pickup_requested_at', $kolom)) {
            $select[] = 'user_missions.pickup_requested_at';
        }

        return DB::table('user_missions')
            ->join('missions', 'missions.mission_id', '=', 'user_missions.mission_id')
            ->where('user_missions.user_id', $userId)
            ->where('user_missions.status', 'berjalan')
            ->select($select)
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function ambilRiwayatMisi($userId): array
    {
        $kolom = $this->kolomUserMissions();

        $select = ['missions.title as judul', 'missions.target', 'missions.unit'];

        if (in_array('progress', $kolom)) {
            $select[] = 'user_missions.progress as progress';
        }
        if (in_array('completed_at', $kolom)) {
            $select[] = 'user_missions.completed_at';
        } elseif (in_array('updated_at', $kolom)) {
            $select[] = 'user_missions.updated_at';
        }

        $orderKolom = in_array('completed_at', $kolom) ? 'user_missions.completed_at' : 'user_missions.updated_at';

        return DB::table('user_missions')
            ->join('missions', 'missions.mission_id', '=', 'user_missions.mission_id')
            ->where('user_missions.user_id', $userId)
            ->where('user_missions.status', 'selesai')
            ->select($select)
            ->latest($orderKolom)
            ->limit(5)
            ->get()
            ->toArray();
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

    private function beroSystemPrompt(): string
    {
        return <<<PROMPT
Kamu adalah "Bero", sahabat lingkungan yang asik, hangat, dan pintar soal sampah.

FOKUS TOPIK:
- Edukasi sampah (jenis, dampak, 3R, cara memilah)
- Tips & trik kelola sampah rumah tangga
- Bank sampah (cara setor, manfaat, lokasi terdekat)
- Misi harian ramah lingkungan
- Reward dan poin warga
- Data CO2 dan Eco Habit Score warga
- Kategori sampah & harga jualnya

GAYA BICARA (WAJIB DIPATUHI):
- Ngobrol seperti teman, bukan seperti robot/AI.
- JANGAN pernah pakai frasa "Sebagai AI...", "Saya adalah asisten...", "Saya tidak punya akses...".
- Maksimal 1-2 emoji per pesan. Jangan berlebihan.
- Hindari bullet panjang. Kalau bisa, ngobrol natural 2-3 kalimat.
- Jangan mengulang pertanyaan user di awal jawaban.

ATURAN DATA (WAJIB):
- Di bawah ini akan ada blok "DATA WARGA (REAL DARI DATABASE)", "DAFTAR KATEGORI SAMPAH", "DAFTAR MISI TERSEDIA", "DAFTAR REWARD TERSEDIA", "DAFTAR BANK SAMPAH", dan "MISI YANG SEDANG DIAMBIL WARGA".
- Gunakan data itu sebagai sumber kebenaran. JANGAN mengarang data yang tidak ada.
- Kalau user tanya "misi hari ini", sebutkan 1-2 misi dari daftar + bank sampahnya.
- Kalau user tanya "reward", sebutkan reward yang poinnya terjangkau dengan total poin user.
- Kalau user tanya "CO2" atau "eco habit", jelaskan berdasarkan angka real user.
- Kalau user tanya "bank sampah terdekat", sebutkan dari daftar bank sampah yang ada.
- Kalau data tidak ada di blok tersebut, jujur bilang belum ada datanya, lalu sarankan langkah lain.

ATURAN KHUSUS KATEGORI SAMPAH & FOTO:
- Kalau user mengirim FOTO, WAJIB identifikasi jenis sampah di foto terlebih dahulu.
- Cocokkan dengan "DAFTAR KATEGORI SAMPAH" yang ada.
- Sebutkan: nama kategori, harga per kg, poin per kg, dan CO2 yang dihemat per kg.
- Contoh gaya: "Itu kayaknya botol plastik PET ya! Harganya sekitar Rp 3.000 per kg, dapat 30 poin, dan bisa hemat 1.5 kg CO2 per kg. Mau Bero bantu cari bank sampah terdekat?"
- Kalau foto tidak jelas atau bukan sampah, tanyakan ke user dengan ramah.
- JANGAN mengarang harga/poin kalau tidak ada di daftar kategori.

ATURAN KHUSUS BANK SAMPAH:
- Kalau user tanya "bank sampah terdekat" atau sejenisnya, dan ada blok "DAFTAR BANK SAMPAH" di bawah, LANGSUNG sebut 1-2 nama bank sampah + alamatnya dari data itu.
- Contoh gaya: "Di daerahmu ada nih, Bank Sampah Melati di Jl. Kebon Jeruk No. 12. Buka Senin-Sabtu jam 8 sampai 4 sore. Mau Bero jelaskan cara setornya?"
- Kalau tidak ada data yang cocok, jujur bilang belum ada datanya, lalu sarankan cek Google Maps atau tanya RT/RW. JANGAN mengarang.
- Jangan pernah bilang "saya tidak punya akses lokasi" — cukup pakai data yang diberikan.

MISI HARIAN:
- Kalau user minta misi, berikan 1 misi konkret & singkat yang bisa dilakukan hari ini (contoh: memilah plastik, timbang anorganik, bikin kompos dari sisa dapur).
- Buat misi terasa menyenangkan, bukan tugas berat.
- Kalau ada misi dari database, prioritaskan itu.

REWARD:
- Kalau user tanya reward, sebutkan reward yang bisa ditukar dengan poin user saat ini.
- Jelaskan secara singkat cara menukarnya.
PROMPT;
    }
}