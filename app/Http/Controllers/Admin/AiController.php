<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiRiwayat;
use App\Models\Balita;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AiController extends Controller
{
    public function sambutan()
    {
        return view('admin.ai.sambutan', $this->desaContext() + [
            'tipe'    => 'sambutan',
            'riwayat' => AiRiwayat::where('tipe', 'sambutan')->latest()->take(30)->get(),
        ]);
    }

    public function perdes()
    {
        return view('admin.ai.perdes', $this->desaContext() + [
            'tipe'    => 'perdes',
            'riwayat' => AiRiwayat::where('tipe', 'perdes')->latest()->take(30)->get(),
        ]);
    }

    public function surat()
    {
        return view('admin.ai.surat', $this->desaContext() + [
            'tipe'    => 'surat',
            'riwayat' => AiRiwayat::where('tipe', 'surat')->latest()->take(30)->get(),
        ]);
    }

    public function perkades()
    {
        return view('admin.ai.perkades', $this->desaContext() + [
            'tipe'    => 'perkades',
            'riwayat' => AiRiwayat::where('tipe', 'perkades')->latest()->take(30)->get(),
        ]);
    }

    public function prediksiStunting()
    {
        $balitas = Balita::with('timbangTerakhir')
            ->orderBy('nama')
            ->get(['id', 'nama', 'tanggal_lahir', 'jenis_kelamin']);

        return view('admin.ai.prediksi-stunting', $this->desaContext() + [
            'tipe'    => 'prediksi-stunting',
            'riwayat' => AiRiwayat::where('tipe', 'prediksi-stunting')->latest()->take(30)->get(),
            'balitas' => $balitas,
        ]);
    }

    public function destroyRiwayat(AiRiwayat $aiRiwayat): JsonResponse
    {
        $aiRiwayat->delete();

        return response()->json(['success' => true]);
    }

    public function generate(Request $request): JsonResponse
    {
        $type = $request->input('type');

        [$system, $user] = match ($type) {
            'sambutan'          => $this->promptSambutan($request),
            'perdes'            => $this->promptPerdes($request),
            'surat'             => $this->promptSurat($request),
            'perkades'          => $this->promptPerkades($request),
            'prediksi-stunting' => $this->promptPrediksiStunting($request),
            default             => [null, null],
        };

        if (! $system) {
            return response()->json(['error' => 'Jenis konten tidak dikenali.'], 422);
        }

        $result = $this->callAi($system, $user);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        $saved = AiRiwayat::create([
            'user_id'   => Auth::id(),
            'tipe'      => $type,
            'parameter' => $request->except(['_token', 'type']),
            'konten'    => $result['content'],
        ]);

        $response = [
            'content' => $result['content'],
            'riwayat' => [
                'id'         => $saved->id,
                'created_at' => $saved->created_at->toISOString(),
                'parameter'  => $saved->parameter,
            ],
        ];

        // Untuk prediksi stunting, parse JSON di server agar lebih handal
        if ($type === 'prediksi-stunting') {
            $parsed = $this->extractJsonFromText($result['content']);
            if ($parsed) {
                $response['parsed'] = $parsed;
            } else {
                return response()->json([
                    'error' => 'AI tidak mengembalikan format JSON yang valid. Coba ulangi analisis.',
                ], 500);
            }
        }

        return response()->json($response);
    }

    // ── Prompt builders ──────────────────────────────────────────────

    private function promptSambutan(Request $request): array
    {
        $desa    = Setting::get('desa.nama', 'Desa');
        $kepala  = Setting::get('desa.kepala', 'Kepala Desa');

        $system = "Kamu adalah asisten penulisan resmi pemerintah desa di Indonesia. "
                . "Tulis dengan bahasa Indonesia yang baik, formal, dan sesuai konteks pemerintahan desa. "
                . "Gunakan gaya sambutan yang hangat namun tetap resmi.";

        $user = "Buatkan kata sambutan Kepala Desa {$desa} atas nama {$kepala} untuk acara berikut:\n"
              . "- Nama acara: " . $request->input('acara') . "\n"
              . "- Tanggal & tempat: " . $request->input('tanggal_tempat') . "\n"
              . "- Peserta/undangan: " . $request->input('peserta') . "\n"
              . "- Poin-poin yang ingin disampaikan: " . $request->input('poin', '-') . "\n"
              . "- Panjang sambutan: " . $request->input('panjang', 'sedang (~400 kata)') . "\n\n"
              . "Tulis kata sambutan lengkap mulai dari salam pembuka, isi, hingga penutup. "
              . "Gunakan paragraf yang jelas dan mudah dibaca saat dibacakan.";

        return [$system, $user];
    }

    private function promptPerdes(Request $request): array
    {
        $desa       = Setting::get('desa.nama', 'Desa');
        $kecamatan  = Setting::get('desa.kecamatan', '');
        $kabupaten  = Setting::get('desa.kabupaten', '');

        $system = "Kamu adalah ahli hukum pemerintahan desa di Indonesia. "
                . "Tulis peraturan desa sesuai format resmi perundang-undangan desa "
                . "mengacu pada UU Desa No. 6 Tahun 2014 dan peraturan turunannya. "
                . "Gunakan struktur BAB, Pasal, dan Ayat yang tepat.";

        $lokasi = implode(', ', array_filter([$kecamatan, $kabupaten]));

        $user = "Buatkan draft Peraturan Desa {$desa}" . ($lokasi ? ", {$lokasi}" : '') . " dengan detail berikut:\n"
              . "- Nomor Perdes: " . $request->input('nomor') . "\n"
              . "- Tentang: " . $request->input('tentang') . "\n"
              . "- Tahun: " . $request->input('tahun') . "\n"
              . "- Latar belakang/pertimbangan: " . $request->input('latar_belakang') . "\n"
              . "- Pokok-pokok pengaturan yang diinginkan: " . $request->input('pokok_pokok') . "\n\n"
              . "Tulis draft lengkap dengan: judul, pembukaan (Menimbang, Mengingat), batang tubuh (BAB I Ketentuan Umum, BAB berikutnya sesuai materi, BAB Penutup), "
              . "dan bagian penutup (tempat/tanggal, tanda tangan Kepala Desa & Sekretaris Desa). "
              . "Isi Pasal-pasal dengan substansi yang relevan berdasarkan pokok-pokok pengaturan di atas.";

        return [$system, $user];
    }

    private function promptSurat(Request $request): array
    {
        $desa      = Setting::get('desa.nama', 'Desa');
        $kepala    = Setting::get('desa.kepala', 'Kepala Desa');
        $kecamatan = Setting::get('desa.kecamatan', '');
        $kabupaten = Setting::get('desa.kabupaten', '');

        $system = "Kamu adalah sekretaris desa ahli dalam penulisan surat resmi pemerintah desa Indonesia. "
                . "Tulis surat dengan format, diksi, dan bahasa sesuai standar administrasi pemerintahan desa. "
                . "Hasilkan hanya isi/body surat (bagian pembuka, isi, dan penutup), "
                . "tidak perlu kop surat, nomor surat, dan tanda tangan karena sudah ada formatnya.";

        $lokasi = implode(', ', array_filter([$kecamatan, $kabupaten]));

        $user = "Buatkan isi surat {$request->input('jenis')} dari Desa {$desa}" . ($lokasi ? ", {$lokasi}" : '') . ".\n"
              . "- Ditujukan kepada: " . $request->input('kepada') . "\n"
              . "- Jabatan/instansi penerima: " . $request->input('jabatan', '-') . "\n"
              . "- Perihal: " . $request->input('perihal') . "\n"
              . "- Tanggal surat: " . $request->input('tanggal') . "\n"
              . "- Poin-poin isi surat: " . $request->input('poin') . "\n\n"
              . "Tulis hanya bagian isi surat: paragraf pembuka (sapaan dan tujuan), "
              . "paragraf isi (poin-poin yang disampaikan), dan paragraf penutup (harapan dan ucapan terima kasih). "
              . "Pengirim adalah {$kepala} Desa {$desa}.";

        return [$system, $user];
    }

    private function promptPerkades(Request $request): array
    {
        $desa      = Setting::get('desa.nama', 'Desa');
        $kepala    = Setting::get('desa.kepala', 'Kepala Desa');
        $kecamatan = Setting::get('desa.kecamatan', '');
        $kabupaten = Setting::get('desa.kabupaten', '');

        $system = "Kamu adalah ahli hukum pemerintahan desa di Indonesia. "
                . "Tulis Peraturan Kepala Desa (Perkades) sesuai format resmi perundang-undangan desa "
                . "mengacu pada UU Desa No. 6 Tahun 2014 dan Permendagri No. 111 Tahun 2014. "
                . "Perkades bersifat pelaksana teknis dari Perdes atau hal-hal yang tidak memerlukan Perdes. "
                . "Gunakan struktur Menimbang, Mengingat, MEMUTUSKAN/Menetapkan, BAB, Pasal yang tepat.";

        $lokasi   = implode(', ', array_filter([$kecamatan, $kabupaten]));
        $dasarHukum = $request->input('dasar_hukum') ?: '-';

        $user = "Buatkan draft Peraturan Kepala Desa {$desa}" . ($lokasi ? ", {$lokasi}" : '') . " dengan detail berikut:\n"
              . "- Nomor Perkades: " . $request->input('nomor') . "\n"
              . "- Tentang: " . $request->input('tentang') . "\n"
              . "- Tahun: " . $request->input('tahun') . "\n"
              . "- Dasar hukum / Perdes yang dijalankan: " . $dasarHukum . "\n"
              . "- Latar belakang/pertimbangan: " . $request->input('latar_belakang') . "\n"
              . "- Pokok-pokok pengaturan yang diinginkan: " . $request->input('pokok_pokok') . "\n\n"
              . "Tulis draft lengkap Perkades dengan: judul (PERATURAN KEPALA DESA {$desa}), "
              . "pembukaan (Dengan Rahmat Tuhan Yang Maha Esa, Menimbang, Mengingat, MEMUTUSKAN, Menetapkan), "
              . "batang tubuh (BAB I Ketentuan Umum, BAB-BAB substantif sesuai materi, BAB Penutup/Ketentuan Penutup), "
              . "dan penutup (tempat, tanggal penetapan, tanda tangan {$kepala}). "
              . "Bedakan dengan Perdes: Perkades lebih singkat, operasional, dan tidak melibatkan BPD.";

        return [$system, $user];
    }

    private function promptPrediksiStunting(Request $request): array
    {
        $nama    = $request->input('nama');
        $jkLabel = $request->input('jenis_kelamin') === 'L' ? 'Laki-laki' : 'Perempuan';
        $umur    = (int) $request->input('umur_bulan');
        $bb      = $request->input('berat_badan');
        $tb      = $request->input('tinggi_badan');
        $lk      = $request->input('lingkar_kepala');
        $catatan = $request->input('catatan');

        $system = "Kamu adalah dokter spesialis gizi anak Indonesia. "
                . "Analisis data antropometri balita berdasarkan WHO Child Growth Standards dan Permenkes RI No. 2/2020. "
                . "OUTPUT: hanya JSON mentah (raw JSON). Jangan gunakan markdown, jangan tambahkan teks penjelasan, jangan gunakan kode blok. "
                . "Mulai respons langsung dengan karakter { dan akhiri dengan }.";

        $user = "Data balita:\n"
              . "Nama: {$nama} | Jenis Kelamin: {$jkLabel} | Usia: {$umur} bulan\n"
              . "Berat Badan: {$bb} kg | Tinggi Badan: {$tb} cm"
              . ($lk      ? " | Lingkar Kepala: {$lk} cm" : '')
              . ($catatan ? "\nCatatan: {$catatan}" : '')
              . "\n\nKriteria Z-score TB/U: normal (≥-2), berisiko_stunting (-2 s/d -2.5), stunting_sedang (-2.5 s/d -3), stunting_berat (<-3).\n\n"
              . "Kembalikan JSON ini (isi nilainya, hapus komentar):\n"
              . '{"status":"normal","tb_u_zscore":0.0,"bb_u_zscore":0.0,"bb_tb_zscore":0.0,'
              . '"kesimpulan":"2-3 kalimat ringkasan kondisi anak",'
              . '"interpretasi_tb_u":"penjelasan status tinggi badan terhadap usia",'
              . '"faktor_risiko":["faktor risiko yang teridentifikasi"],'
              . '"rekomendasi":["langkah tindakan konkret untuk kader posyandu dan keluarga"],'
              . '"rekomendasi_makanan":['
              .   '{"nama":"nama bahan makanan","manfaat":"manfaat gizi spesifik","contoh_menu":"contoh menu/cara pemberian sesuai usia"}],'
              . '"suplemen_gizi":["suplemen atau fortifikasi yang dianjurkan jika diperlukan, kosongkan array jika tidak perlu"],'
              . '"pola_makan":"penjelasan singkat pola makan ideal (frekuensi, porsi, variasi) sesuai usia balita ini",'
              . '"perlu_rujukan":false,'
              . '"catatan_klinis":"catatan klinis tambahan atau string kosong"}';

        return [$system, $user];
    }

    private function extractJsonFromText(string $text): ?array
    {
        // Hapus markdown code block jika ada
        $text = preg_replace('/^```(?:json)?\s*/im', '', $text);
        $text = preg_replace('/```\s*$/im', '', $text);
        $text = trim($text);

        // Coba parse langsung
        $decoded = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Ekstrak blok {...} pertama yang valid
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // Fallback: cari dari { pertama sampai } terakhir
        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $decoded = json_decode(substr($text, $start, $end - $start + 1), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    // ── AI API caller ────────────────────────────────────────────────

    private function callAi(string $system, string $user): array
    {
        $active = Setting::get('api.active') ?? 'openai';
        $model  = Setting::get("api.{$active}.model");
        $key    = Setting::get("api.{$active}.key");

        // Backward compat: fall back ke key lama
        if (! $key) {
            $key   = Setting::get('api.key');
            $model = $model ?: Setting::get('api.model') ?? 'gpt-4o-mini';
        }

        if (! $key) {
            return ['error' => 'Kunci API belum dikonfigurasi. Buka Pengaturan → API untuk mengisi kunci.'];
        }

        $model       = $model ?: 'gpt-4o-mini';
        $isAnthropic = $active === 'anthropic' || str_starts_with($model, 'claude');

        try {
            if ($isAnthropic) {
                return $this->callAnthropic($key, $model, $system, $user);
            }
            if ($active === 'custom') {
                $baseUrl = Setting::get('api.custom.url');
                return $this->callOpenAi($key, $model, $system, $user, $baseUrl ?: null);
            }
            return $this->callOpenAi($key, $model, $system, $user);
        } catch (\Exception $e) {
            return ['error' => 'Gagal menghubungi API: ' . $e->getMessage()];
        }
    }

    private function callAnthropic(string $key, string $model, string $system, string $user): array
    {
        $response = Http::timeout(120)
            ->withHeaders([
                'x-api-key'         => $key,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => $model,
                'max_tokens' => 4096,
                'system'     => $system,
                'messages'   => [['role' => 'user', 'content' => $user]],
            ]);

        if ($response->successful()) {
            return ['content' => $response->json('content.0.text') ?? ''];
        }

        $err = $response->json('error.message') ?? $response->body();
        return ['error' => "Anthropic API error: {$err}"];
    }

    private function callOpenAi(string $key, string $model, string $system, string $user, ?string $baseUrl = null): array
    {
        $endpoint = rtrim($baseUrl ?: 'https://api.openai.com', '/') . '/v1/chat/completions';
        $response = Http::timeout(120)
            ->withToken($key)
            ->post($endpoint, [
                'model'      => $model,
                'max_tokens' => 1200,
                'messages'   => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user',   'content' => $user],
                ],
            ]);

        if ($response->successful()) {
            return ['content' => $response->json('choices.0.message.content') ?? ''];
        }

        $err = $response->json('error.message') ?? $response->body();
        return ['error' => "OpenAI API error: {$err}"];
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function desaContext(): array
    {
        $active   = Setting::get('api.active') ?? 'openai';
        $apiReady = (bool) Setting::get("api.{$active}.key")
                 || (bool) Setting::get('api.key'); // backward compat

        $logoPath = Setting::get('desa.logo');

        return [
            'desaNama'       => Setting::get('desa.nama', 'Desa'),
            'desaKecamatan'  => Setting::get('desa.kecamatan', ''),
            'desaKabupaten'  => Setting::get('desa.kabupaten', ''),
            'desaProvinsi'   => Setting::get('desa.provinsi', ''),
            'desaKepala'     => Setting::get('desa.kepala', ''),
            'desaSekretaris' => Setting::get('desa.sekretaris', ''),
            'desaLogoUrl'    => $logoPath ? Storage::url($logoPath) : null,
            'apiReady'       => $apiReady,
        ];
    }
}
