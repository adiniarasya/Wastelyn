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
use Illuminate\Support\Facades\Storage;
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

        $pesanUser = trim($request->input('message') ?? '');
        $adaGambar = $request->hasFile('image');

        $userMessage = AiChatMessage::create([
            'session_id' => $session->session_id,
            'sender' => 'user',
            'message' => $pesanUser !== ''
                ? $pesanUser
                : '[user mengirim gambar tanpa teks]',
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

        $intent = $this->deteksiIntent($pesanUser, $adaGambar);

        if ($intent === 'sapaan' && !$adaGambar) {
            try {
                $reply = $this->gemini->chat(
                    message: $pesanUser,
                    history: $history,
                    systemPrompt: $this->beroSystemPrompt()
                    . "\n\n=== MODE: SAPAAN ===\n"
                    . "User cuma nyapa. Balas singkat (1-2 kalimat), santai, "
                    . "lalu tanya balik ada yang bisa dibantu soal sampah/lingkungan. "
                    . "JANGAN dump data apapun dan JANGAN kirim gambar.",
                    image: null,
                );
            } catch (\Throwable $e) {
                Log::error('Gemini error (sapaan)', [
                    'session_id' => $session->session_id,
                    'user_id' => auth()->id(),
                    'message' => $e->getMessage(),
                ]);

                return $this->respond($request, $session, [
                    'error' => $this->pesanErrorRamah($e),
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

        $konteksTambahan = '';
        if ($pesanUser && $this->butuhBankSampah($pesanUser)) {
            $user = auth()->user();
            $lat = $user->latitude ?? null;
            $lng = $user->longitude ?? null;

            if ($lat && $lng) {
                $hasil = $this->bankSampah->cariTerdekat((float) $lat, (float) $lng, 5);
            } else {
                $hasil = $this->bankSampah->cariRelevan($pesanUser, 5);
            }

            $konteksTambahan = $this->bankSampah->formatUntukPrompt($hasil);
        }

        $dataKonteks = $this->siapkanDataKonteks($pesanUser, $adaGambar, $intent, $session);

        try {
            $reply = $this->gemini->chat(
                message: $pesanUser ?: '[user mengirim gambar tanpa teks]',
                history: $history,
                systemPrompt: $this->beroSystemPrompt() . $konteksTambahan . $dataKonteks,
                image: $request->file('image'),
            );
        } catch (\Throwable $e) {
            Log::error('Gemini error', [
                'session_id' => $session->session_id,
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
            ]);

            return $this->respond($request, $session, [
                'error' => $this->pesanErrorRamah($e),
            ], 500);
        }

        $parsed = $this->gemini->extractImageMarkers($reply);
        $attachment = $this->resolveAttachment($parsed['images']);

        $botMessage = AiChatMessage::create([
            'session_id' => $session->session_id,
            'sender' => 'bero',
            'message' => $parsed['text'],
        ]);

        if (!empty($attachment)) {
            $botMessage->update([
                'attachment' => json_encode($attachment),
            ]);
        }

        return $this->respond($request, $session, [
            'user_message' => $userMessage,
            'bot_message' => $botMessage->fresh(),
        ]);
    }

    private function pesanErrorRamah(\Throwable $e): string
    {
        $msg = strtolower($e->getMessage());

        if (str_contains($msg, '503') || str_contains($msg, 'high demand') || str_contains($msg, 'overloaded') || str_contains($msg, 'unavailable')) {
            return 'Bero lagi rame banget nih, coba kirim ulang dalam beberapa detik ya';
        }

        if (str_contains($msg, '429') || str_contains($msg, 'quota') || str_contains($msg, 'rate limit')) {
            return 'Bero lagi istirahat sebentar, coba lagi nanti ya';
        }

        if (str_contains($msg, 'timeout') || str_contains($msg, 'timed out')) {
            return 'Koneksi ke Bero lambat, coba kirim ulang ya';
        }

        if (str_contains($msg, 'curl error') || str_contains($msg, 'connection') || str_contains($msg, 'resolving') || str_contains($msg, 'recv failure')) {
            return 'Koneksi ke Bero lagi putus, coba kirim ulang ya';
        }

        if (str_contains($msg, '404') || str_contains($msg, 'no longer available') || str_contains($msg, 'not found')) {
            return 'Bero lagi ada masalah model AI, hubungi admin ya';
        }

        if (str_contains($msg, '401') || str_contains($msg, '403') || str_contains($msg, 'api key')) {
            return 'Bero lagi ada masalah konfigurasi, hubungi admin ya';
        }

        return 'Bero lagi ada kendala teknis, coba lagi sebentar ya';
    }

    private function resolveAttachment(array $images): array
    {
        if (empty($images)) {
            return [];
        }

        $resolved = [];

        foreach ($images as $img) {
            $tipe = $img['tipe'];
            $kode = $img['kode'];

            if ($tipe === 'kategori') {
                $kategori = WasteCategory::where('name', 'like', '%' . str_replace('_', ' ', $kode) . '%')
                    ->first();

                if ($kategori && !empty($kategori->icon)) {
                    $resolved[] = [
                        'tipe' => 'kategori',
                        'kode' => $kode,
                        'nama' => $kategori->name,
                        'url' => $this->normalizeImageUrl($kategori->icon),
                        'caption' => 'Contoh: ' . $kategori->name,
                    ];
                }
            } elseif ($tipe === 'reward') {
                $reward = Reward::where('name', 'like', '%' . str_replace('_', ' ', $kode) . '%')
                    ->orWhere('reward_id', $kode)
                    ->first();

                if ($reward && !empty($reward->image)) {
                    $resolved[] = [
                        'tipe' => 'reward',
                        'kode' => $kode,
                        'nama' => $reward->name,
                        'url' => $this->normalizeImageUrl($reward->image),
                        'caption' => $reward->name . ' (' . ($reward->point_required ?? 0) . ' poin)',
                    ];
                }
            }
        }

        return $resolved;
    }

    private function normalizeImageUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/storage/', 'storage/'])) {
            return asset(ltrim($path, '/'));
        }

        return Storage::url($path);
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

    private function deteksiIntent(string $pesan, bool $adaGambar): string
    {
        $pesan = trim($pesan);

        if (preg_match('/^(hai|hi|halo|hello|hey|pagi|siang|sore|malam|bro|bos|kak|min|woi|woy|assalamualaikum|salam)[\s!?.,]*$/i', $pesan)) {
            return 'sapaan';
        }

        if ($adaGambar) {
            return 'foto_sampah';
        }

        if (preg_match('/contoh|gambarin|kasih\s*lihat|liatin|foto(nya)?|gambar(nya)?|kayak\s*apa|bentuk(nya)?/i', $pesan)) {
            if ($this->butuhBankSampah($pesan) || preg_match('/sampah|plastik|kertas|kaca|logam|organik|anorganik|b3|elektronik|kaleng|botol/i', $pesan)) {
                return 'contoh_sampah';
            }
            if (preg_match('/reward|hadiah|tukar|poin|point/i', $pesan)) {
                return 'contoh_reward';
            }
        }

        if ($this->butuhBankSampah($pesan)) {
            return 'bank_sampah';
        }
        if (preg_match('/misi|mission|tugas|tantangan|kegiatan/i', $pesan)) {
            return 'misi';
        }
        if (preg_match('/reward|hadiah|tukar|poin|point/i', $pesan)) {
            return 'reward';
        }
        if (preg_match('/co2|karbon|emisi|jejak|eco\s*habit|skor|score/i', $pesan)) {
            return 'co2';
        }
        if (preg_match('/kategori|jenis\s*sampah|harga\s*sampah|plastik|kertas|kaca|logam|organik|anorganik|b3|elektronik|kaleng|botol/i', $pesan)) {
            return 'kategori';
        }

        return 'umum';
    }

    private function butuhBankSampah(string $pesan): bool
    {
        return (bool) preg_match('/bank\s*sampah|tempat sampah|buang sampah|daur ulang|setor sampah|wastebank|waste\s*bank|tps|tpa|terdekat|dekat\s*sini|sekitar\s*sini/i', $pesan);
    }

    private function siapkanDataKonteks(
        string $pesanUser,
        bool $adaGambar = false,
        string $intent = 'umum',
        ?AiChatSession $session = null
    ): string {
        $user = auth()->user();
        if (!$user || !$user->user_id) {
            return '';
        }

        $userId = $user->user_id;
        $userPoints = (int) ($user->points ?? 0);

        $butuhKategori = $adaGambar
            || in_array($intent, ['foto_sampah', 'kategori', 'contoh_sampah'], true)
            || preg_match('/kategori|jenis\s*sampah|sampah\s*apa|ini\s*sampah|foto\s*sampah|gambar\s*sampah|harga\s*sampah|plastik|kertas|kaca|logam|organik|anorganik|b3|elektronik|kaleng|botol/i', $pesanUser);

        $butuhMisi = in_array($intent, ['misi'], true)
            || preg_match('/misi|mission|tugas|tantangan|kegiatan/i', $pesanUser);

        $butuhReward = in_array($intent, ['reward', 'contoh_reward'], true)
            || preg_match('/reward|hadiah|tukar|poin|point/i', $pesanUser);

        $butuhCo2 = in_array($intent, ['co2'], true)
            || preg_match('/co2|karbon|emisi|jejak|lingkungan|eco\s*habit|skor|score/i', $pesanUser);

        $butuhBank = in_array($intent, ['bank_sampah'], true)
            || $this->butuhBankSampah($pesanUser);

        $konteks = "\n\n=== VIBE PERCAKAPAN ===\n";
        if ($session) {
            $jumlahPesan = $session->messages()->count();
            $konteks .= "Ini percakapan ke-{$jumlahPesan} di sesi ini.\n";
            $konteks .= "Judul sesi: " . ($session->title ?? '-') . "\n";
        }
        $konteks .= "Intent terdeteksi: {$intent}\n";
        $konteks .= "Gaya respons: santai, ngobrol, jangan formal. Kalau datanya gak relevan, skip aja.\n";

        $konteks .= "\n=== DATA WARGA (REAL DARI DATABASE) ===\n";
        $konteks .= "Nama: " . ($user->name ?? '-') . "\n";
        $konteks .= "Kota/Alamat: " . ($user->address ?? '-') . "\n";
        $konteks .= "Koordinat: " . (($user->latitude ?? null) ? "{$user->latitude}, {$user->longitude}" : 'tidak tersedia') . "\n";
        $konteks .= "Total Poin: {$userPoints}\n";
        $konteks .= "XP: " . ($user->xp ?? 0) . "\n";
        $konteks .= "Level: " . ($user->level ?? 1) . "\n";

        $konteks .= "\n!!! ATURAN MUTLAK POIN !!!\n";
        $konteks .= "Poin user saat ini = {$userPoints}. Ini angka REAL dari database.\n";
        $konteks .= "DILARANG KERAS menyebut angka poin selain {$userPoints}.\n";
        $konteks .= "DILARANG bilang poin 0 kalau angkanya bukan 0.\n";
        $konteks .= "Kalau user tanya 'poin aku berapa', jawab: poin kamu {$userPoints}.\n";
        $konteks .= "Kalau user tanya reward apa yang bisa ditukar, bandingkan harga reward dengan {$userPoints}.\n";

        if ($butuhKategori) {
            $kategori = WasteCategory::query()
                ->limit(50)
                ->get()
                ->map(function ($k) {
                    return [
                        'nama' => $k->name ?? '-',
                        'kode_gambar' => Str::slug($k->name ?? ''),
                        'deskripsi' => $k->description ?? '-',
                        'harga_per_kg' => $k->price_per_kg ?? 0,
                        'reward_per_kg' => $k->reward_per_kg ?? 0,
                        'point_per_kg' => $k->point_per_kg ?? 0,
                        'co2_saved_per_kg' => $k->co2_saved_per_kg ?? 0,
                        'punya_gambar' => !empty($k->icon),
                    ];
                })
                ->toArray();

            $konteks .= "\nDAFTAR KATEGORI SAMPAH (REAL):\n"
                . json_encode($kategori, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

            $konteks .= "\nCARA KIRIM GAMBAR CONTOH SAMPAH:\n";
            $konteks .= "- Kalau user minta 'contoh', 'gambarin', 'kayak apa', 'liatin', atau 'foto', KIRIM marker: [GAMBAR:kategori:KODE]\n";
            $konteks .= "- Ganti KODE dengan 'kode_gambar' dari daftar kategori di atas.\n";
            $konteks .= "- Cuma kirim kalau 'punya_gambar' = true. Kalau false, jangan kirim marker.\n";
            $konteks .= "- Contoh: 'Nih contoh botol PET [GAMBAR:kategori:botol-plastik-pet] kira-kira kayak gitu.'\n";
            $konteks .= "- Maksimal 1 gambar per pesan.\n";
            $konteks .= "- Jangan kirim marker di awal kalimat, taruh di tengah atau akhir biar natural.\n";
        }

        if ($butuhMisi) {
            $misiTersedia = Mission::query()
                ->limit(10)
                ->get()
                ->map(function ($m) {
                    $bank = $m->bank_id ? WasteBank::find($m->bank_id) : null;
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
                        'bank_sampah' => $bank ? $bank->name : null,
                        'bank_alamat' => $bank ? $bank->address : null,
                    ];
                })
                ->toArray();

            $konteks .= "\nDAFTAR MISI TERSEDIA:\n"
                . json_encode($misiTersedia, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

            $misiUser = $this->ambilMisiUser($userId);
            if (!empty($misiUser)) {
                $konteks .= "\nMISI YANG SEDANG DIAMBIL WARGA:\n"
                    . json_encode($misiUser, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }

            $konteks .= "\nCATATAN MISI: Kalau user nanya misi, sebut 1 misi konkret + bank sampahnya di mana. "
                . "Kalau user di kota yang sama dengan bank sampah misi, sebut 'di deket kamu tuh'.\n";
        }

        if ($butuhReward) {
            $rewardTersedia = Reward::query()
                ->limit(15)
                ->get()
                ->map(function ($r) {
                    return [
                        'nama' => $r->name ?? '-',
                        'kode_gambar' => Str::slug($r->name ?? ''),
                        'deskripsi' => $r->description ?? '-',
                        'point_dibutuhkan' => $r->point_required ?? 0,
                        'stok' => $r->stock ?? 0,
                        'punya_gambar' => !empty($r->image),
                        'status' => $r->status ?? '-',
                        'cara_ambil' => $r->delivery_instructions ?? '-',
                    ];
                })
                ->toArray();

            $konteks .= "\nDAFTAR REWARD TERSEDIA:\n"
                . json_encode($rewardTersedia, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            $konteks .= "\nCATATAN POIN USER: Poin user saat ini = {$userPoints}. "
                . "Sebut hanya reward yang 'point_dibutuhkan' <= {$userPoints}. "
                . "Jangan sebut reward yang butuh poin lebih besar dari {$userPoints}.\n";

            $konteks .= "\nCARA KIRIM GAMBAR CONTOH REWARD:\n";
            $konteks .= "- Kalau user minta 'contoh reward', 'rewardnya kayak apa', 'liatin', KIRIM marker: [GAMBAR:reward:KODE]\n";
            $konteks .= "- Ganti KODE dengan 'kode_gambar' dari daftar reward di atas.\n";
            $konteks .= "- Cuma kirim kalau 'punya_gambar' = true.\n";
            $konteks .= "- Contoh: 'Nih tas kainnya [GAMBAR:reward:tas-kain] tinggal tuker 250 poin.'\n";
            $konteks .= "- Maksimal 1 gambar per pesan.\n";
        }

        if ($butuhCo2) {
            $riwayatCo2 = $this->ambilRiwayatMisi($userId);
            if (!empty($riwayatCo2)) {
                $konteks .= "\nRIWAYAT MISI SELESAI (konteks CO2):\n"
                    . json_encode($riwayatCo2, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
        }

        if ($butuhBank) {
            $userLat = $user->latitude ?? null;
            $userLng = $user->longitude ?? null;

            if ($userLat && $userLng) {
                $bankList = $this->bankSampah->cariTerdekat((float) $userLat, (float) $userLng, 5);
                $konteks .= "\nDAFTAR BANK SAMPAH TERDEKAT DARI LOKASI USER (udah diurutin):\n";
            } else {
                $bankList = $this->bankSampah->cariRelevan($pesanUser, 5);
                $konteks .= "\nDAFTAR BANK SAMPAH (user belum share lokasi):\n";
            }

            if (!empty($bankList)) {
                $konteks .= json_encode($bankList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
                $konteks .= "\nCATATAN: ";
                $konteks .= $userLat && $userLng
                    ? "Sebut 1-2 bank sampah paling dekat, sebutkan jarak dalam km. Contoh: 'Bank Sampah Melati, sekitar 2.3 km dari kamu.'\n"
                    : "User belum share lokasi. Sebut 1-2 bank sampah aja, bilang 'aku belum tau lokasi kamu persisnya, tapi ini yang ada di data.'\n";
            } else {
                $konteks .= "Belum ada bank sampah di database.\n";
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

        if (in_array('progress', $kolom))
            $select[] = 'user_missions.progress as progress';
        if (in_array('unique_code', $kolom))
            $select[] = 'user_missions.unique_code';
        if (in_array('started_at', $kolom))
            $select[] = 'user_missions.started_at';
        if (in_array('pickup_requested_at', $kolom))
            $select[] = 'user_missions.pickup_requested_at';

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

        if (in_array('progress', $kolom))
            $select[] = 'user_missions.progress as progress';
        if (in_array('completed_at', $kolom)) {
            $select[] = 'user_missions.completed_at';
        } elseif (in_array('updated_at', $kolom)) {
            $select[] = 'user_missions.updated_at';
        }

        $orderKolom = in_array('completed_at', $kolom)
            ? 'user_missions.completed_at'
            : 'user_missions.updated_at';

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
        $flash = $data[$key] ?? ($data['bot_message']->message ?? 'OK');

        return redirect()
            ->route('user.ai-chat-sessions.show', $session->session_id)
            ->with($key, $flash);
    }

    private function beroSystemPrompt(): string
    {
        return <<<PROMPT
Kamu itu Bero — AI assistant resmi WasteLyn, teman ngobrol soal sampah dan lingkungan khusus untuk warga Indonesia. Anggap aja kamu kayak kakak tingkat yang udah paham dunia bank sampah, tapi ngobrolnya santai kayak di warung kopi.

TENTANG WASTELYN:
- WasteLyn = platform pembentukan kebiasaan pengelolaan sampah untuk warga Indonesia.
- Fitur: Waste Mission (misi sampah), Setoran Sampah, Smart Waste Network, Eco Habit Score, Bero AI (kamu), Reward Center.
- Setiap sampah yang disetor ke bank sampah partner → dapat poin, XP, level, dan nyumbang ke CO2 saved.
- Warga punya: poin, XP, level, Eco Habit Score, Total CO2 Tersimpan.
- Kamu bisa bantu user: cari bank sampah terdekat, jelasin misi, identifikasi sampah dari foto, kasih info harga, tuker poin jadi reward.

CARA NGOMONG:
- Kayak lagi chat WhatsApp sama temen. Boleh "eh", "nih", "loh", "sip", "oke deh", "wah".
- Kalau user curhat soal sampah numpuk, respons dulu kayak manusia: "aduh iya, numpuk gitu emang bikin pusing ya". Baru kasih solusi.
- Kalau user ketawa atau becanda, ikut ketawa dulu. Jangan langsung jawab serius.
- Kalau user nanya singkat, jawab singkat. Jangan malah ceramah.
- Panjang ideal: 1-3 kalimat. Kalau perlu detail, baru lebih.
- Emoji: 0-2 per pesan. Kalau user gak pake emoji, kamu juga jangan.
- HINDARI frasa: "Tentu!", "Baik!", "Sebagai AI", "Saya di sini untuk membantu", "Semoga membantu", "Jangan ragu untuk bertanya", "Apakah ada yang bisa saya bantu".

KONTEKS INDONESIA (WAJIB PAHAM):
- Jenis sampah: plastik (botol PET, gelas plastik, kantong kresek, sedotan), kertas (kardus, HVS, koran), logam (kaleng aluminium, besi, tembaga), kaca (botol beling), organik (sisa makanan, daun, sayur busuk), B3 (baterai, lampu, elektronik), tekstil (kain bekas, baju).
- Istilah lokal: bank sampah, TPS (Tempat Pembuangan Sementara), TPA (Tempat Pembuangan Akhir), TPS3R, maggot BSF, kompos, ecobrick, nasabah bank sampah, tabungan sampah, sampah residu.
- Harga pasar (per kg, perkiraan, sebutkan "sekitar" atau "kisaran"):
  - Botol PET bersih: Rp 3.000-5.000
  - Gelas plastik bersih: Rp 4.000-6.000
  - Kardus: Rp 1.500-2.500
  - Kaleng aluminium: Rp 12.000-18.000
  - Besi: Rp 3.000-5.000
  - Tembaga: Rp 60.000-90.000
  - Botol kaca: Rp 200-500 per buah
  - Harga bisa beda per daerah — selalu bilang "tergantung lokasi".
- Regulasi: UU No. 18/2008 tentang Pengelolaan Sampah, Perpres 97/2017, target Indonesia Bersih Sampah 2025, larangan kantong plastik di beberapa daerah.
- 3R: Reduce, Reuse, Recycle. Plus: Rot (kompos), Replace (ganti alternatif).
- Pemilahan: organik vs anorganik vs B3.

CARA PAKAI DATA:
- Kamu punya data user di bawah. Pakai kalau relevan, JANGAN dibacain kayak laporan.
- Salah: "Total Poin Anda adalah 300."
- Bener: "Poin kamu 300, udah bisa tuker reward kecil tuh."
- Kalau data gak ada, bilang aja "waduh aku belum ada infonya" — jangan ngarang.

ATURAN POIN (MUTLAK):
- Poin user ada di "DATA WARGA" dengan label "Total Poin". Itu angka REAL.
- WAJIB pakai angka persis itu. DILARANG KERAS menyebut angka lain.
- DILARANG ngarang poin.

KALAU USER KIRIM FOTO SAMPAH:
- Liat fotonya, tebak jenis pakai istilah lokal: "itu kayaknya botol PET ya", "wah kayaknya gelas plastik air mineral".
- Kalau ragu: "ini botol PET atau gelas plastik?"
- Kasih info harga/poin/CO2 kalau ada di daftar kategori.
- Contoh: "Wah itu botol PET ya? Bisa dapet sekitar Rp 3.000-5.000/kg, tergantung bank sampahnya. Mau aku cariin bank sampah terdekat?"

KALAU USER MINTA CONTOH SAMPAH:
- Cek daftar kategori, cari yang 'punya_gambar' = true.
- Sisipkan [GAMBAR:kategori:KODE] di tengah/akhir kalimat.
- Contoh: "Nih contohnya [GAMBAR:kategori:botol-plastik-pet] kira-kira kayak gitu. Kalo udah 1 kg bisa dapet sekitar Rp 3.000 loh."
- Maksimal 1 gambar per pesan.

KALAU USER NANYA BANK SAMPAH TERDEKAT:
- Data bank sampah di bawah udah diurutin dari TERDEKAT kalau user share lokasi.
- Kalau ada 'jarak_km', sebut: "Bank Sampah X, sekitar 2.3 km dari lokasi kamu, di Jl. ... Buka jam ..."
- Kalau jarak_km null, bilang: "Aku belum tau lokasi kamu persisnya, tapi ini bank sampah di data: ..."
- Sebut 1-2 bank sampah paling relevan aja. Jangan baca tabel.

KALAU USER NANYA MISI:
- Data misi di bawah udah include nama bank sampahnya.
- Sebut 1 misi konkret + bank sampahnya di mana.
- Contoh: "Ada misi 'Kumpulin 5 kg plastik' dari Bank Sampah Melati di Jl. Kebon Jeruk. Reward-nya 50 poin + 100 XP. Mau ambil?"

KALAU USER NANYA REWARD:
- Sebut reward yang poinnya masuk sama poin user. Jangan sebut semua.
- Kalau user minta contoh gambarnya, sisipkan [GAMBAR:reward:KODE].
- Contoh: "Nih tas kainnya [GAMBAR:reward:tas-kain] tinggal tuker 250 poin aja. Mau?"

KALAU USER NANYA CARA SETOR SAMPAH:
- Jelasin: daftar jadi nasabah bank sampah → pilah sampah dari rumah → timbang di bank sampah → saldo masuk buku tabungan sampah.
- Bisa juga: lewat aplikasi WasteLyn, upload foto sampah buat validasi AI, dapet poin langsung.

KALAU USER NANYA SOAL REGULASI / KEBIJAKAN:
- Sebut singkat: UU 18/2008, Perpres 97/2017, target Bebas Sampah 2025, larangan plastik di daerah tertentu.
- Cukup 1-2 kalimat relevan.

KALAU USER NANYA SOAL KOMPOS / ORGANIK:
- Cara simpel: siapkan wadah, lapisan tanah + sampah organik + tanah, tunggu 4-6 minggu.
- Atau metode takakura / komposter ember.
- Contoh: "Bisa banget! Pakai ember aja, lapisan tanah-sampah-tanah, tutup rapat. 4-6 minggu jadi kompos."

KALAU USER NANYA SOAL B3 / ELEKTRONIK:
- Ingatkan jangan dibuang ke TPS biasa. Harus ke tempat khusus.
- Contoh: "Baterai sama lampu jangan dibuang ke TPS biasa ya, itu B3. Ada dropbox e-waste di beberapa bank sampah."

ATURAN MARKER GAMBAR:
- Format: [GAMBAR:tipe:kode]
- Tipe: 'kategori' atau 'reward'
- Kode: ambil dari 'kode_gambar' di data.
- Cuma kirim kalau user minta contoh/lihat gambarnya.
- Jangan kirim marker di awal kalimat.

CONTOH RESPON:

User: "halo"
Bero: "Halo juga! Lagi ngapain nih? Mau cerita soal sampah atau cari bank sampah?"

User: "bank sampah terdekat dimana?"
Bero: "Dari lokasi kamu, yang paling deket Bank Sampah Melati nih, sekitar 2.3 km. Di Jl. Kebon Jeruk No. 12, buka Senin-Sabtu 8-4. Mau aku jelasin cara setornya?"

User: "sampah plastik aku numpuk nih"
Bero: "Waduh, numpuk gitu emang bikin males ya. Tapi tenang, plastik itu paling gampang dijual kok. Botol PET sekarang sekitar Rp 3.000-5.000/kg. Mau aku kasih tau cara pilahnya?"

User: "contoh botol plastik kayak apa?"
Bero: "Nih contohnya [GAMBAR:kategori:botol-plastik-pet] kira-kira kayak gitu. Kalau udah 1 kg bisa dapet sekitar Rp 3.000 loh!"

User: "misi hari ini apa?"
Bero: "Ada misi 'Kumpulin 5 kg plastik' dari Bank Sampah Melati, 2 km dari kamu. Reward 50 poin + 100 XP. Mau ambil?"

User: "reward tas kain ada?"
Bero: "Ada dong! Nih bentuknya [GAMBAR:reward:tas-kain] tinggal tuker 250 poin aja. Mau?"

User: "makasih bero"
Bero: "Sip, santai aja! Kalau ada yang bingung lagi, tanya aja ya"

PRINSIP UTAMA:
Kalau ragu antara "jawab lengkap" vs "jawab natural", pilih natural. User bisa tanya lanjut kalau kurang.
PROMPT;
    }
}