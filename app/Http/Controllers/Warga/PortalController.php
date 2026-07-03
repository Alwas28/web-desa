<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\AiRiwayat;
use App\Models\LaporanWarga;
use App\Models\Berita;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\Setting;
use App\Models\PengajuanPenjual;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PortalController extends Controller
{
    private function base(): array
    {
        /** @var User $user */
        $user     = Auth::user();
        $penduduk = $user->penduduk_id
            ? Penduduk::with('kartuKeluarga')->find($user->penduduk_id)
            : null;
        $desa = Setting::forGroup('desa.');

        return compact('user', 'penduduk', 'desa');
    }

    public function beranda()
    {
        $base = $this->base();
        /** @var User $user */
        $user = $base['user'];

        return view('mobile.beranda', array_merge($base, [
            'laporan' => LaporanWarga::where('user_id', $user->id)->latest()->take(20)->get(),
            'layanan'        => JenisSurat::aktif()
                                    ->where('dapat_dibuat_masyarakat', true)
                                    ->select('jenis_surats.*')
                                    ->selectRaw('(SELECT COUNT(*) FROM pengajuan_surats WHERE pengajuan_surats.jenis_surat = jenis_surats.nama) as jumlah_pengajuan')
                                    ->orderByDesc('jumlah_pengajuan')
                                    ->orderBy('urutan')
                                    ->take(4)->get(),
            'berita'         => Berita::where('status', 'diterbitkan')
                                    ->latest('published_at')
                                    ->take(4)->get(),
            'produkTerbaru'  => Produk::where('status', 'aktif')
                                    ->with('penduduk')
                                    ->latest()
                                    ->take(6)->get(),
            'produkTerlaris' => Produk::where('status', 'aktif')
                                    ->with('penduduk')
                                    ->oldest()
                                    ->take(6)->get(),
        ]));
    }

    public function layanan()
    {
        $base     = $this->base();
        $penduduk = $base['penduduk'];

        $riwayat   = collect();
        $anggotaKK = collect();

        if ($penduduk) {
            if ($penduduk->isKepalaKeluarga()) {
                // KK head — riwayat seluruh anggota keluarga, plus pilihan penerima surat
                $memberIds = Penduduk::where('kk_id', $penduduk->kk_id)->pluck('id');
                $riwayat   = PengajuanSurat::whereIn('penduduk_id', $memberIds)
                                 ->with('penduduk', 'diajukanOleh')
                                 ->latest()->get();
                $anggotaKK = $penduduk->anggotaKeluarga();
            } else {
                // Anggota biasa — hanya riwayat sendiri
                $riwayat = PengajuanSurat::where('penduduk_id', $penduduk->id)
                               ->latest()->get();
            }
        }

        return view('mobile.layanan', array_merge($base, [
            'layanan'   => JenisSurat::aktif()
                               ->where('dapat_dibuat_masyarakat', true)
                               ->orderBy('urutan')->orderBy('nama')
                               ->get(),
            'riwayat'   => $riwayat,
            'anggotaKK' => $anggotaKK,
        ]));
    }

    public function berita()
    {
        return view('mobile.berita', array_merge($this->base(), [
            'berita' => Berita::where('status', 'diterbitkan')
                            ->latest('published_at')
                            ->get(),
        ]));
    }

    public function akun()
    {
        return view('mobile.akun', $this->base());
    }

    public function akunEdit()
    {
        return view('mobile.akun-edit', $this->base());
    }

    public function updateProfil(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'              => 'required|string|max:100',
            'pekerjaan'         => 'nullable|string|max:100',
            'pendidikan'        => 'nullable|in:tidak_sekolah,sd,smp,sma,diploma,s1,s2,s3',
            'status_perkawinan' => 'nullable|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'agama'             => 'nullable|string|max:50',
            'golongan_darah'    => 'nullable|in:A,B,AB,O,tidak_tahu',
        ]);

        $user->name = $request->name;
        $user->save();

        if ($user->penduduk) {
            $user->penduduk->update($request->only([
                'pekerjaan', 'pendidikan', 'status_perkawinan', 'agama', 'golongan_darah',
            ]));
        }

        return redirect()->route('portal.akun')->with('toast_success', 'Data diri berhasil diperbarui.');
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password'      => 'required|current_password',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update(['password' => bcrypt($request->password)]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    public function laporanStore(Request $request): JsonResponse
    {
        $request->validate([
            'kategori' => 'required|string|max:50',
            'judul'    => 'required|string|max:200',
            'isi'      => 'required|string|max:2000',
        ]);

        $laporan = LaporanWarga::create([
            'user_id'  => Auth::id(),
            'kategori' => $request->kategori,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
        ]);

        return response()->json([
            'laporan' => [
                'id'         => $laporan->id,
                'kategori'   => $laporan->kategori,
                'judul'      => $laporan->judul,
                'isi'        => $laporan->isi,
                'status'     => $laporan->status,
                'balasan'    => $laporan->balasan,
                'created_at' => $laporan->created_at->toISOString(),
            ],
        ]);
    }

    public function laporanDestroy(LaporanWarga $laporan): JsonResponse
    {
        if ($laporan->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $laporan->delete();

        return response()->json(['success' => true]);
    }

    public function produk()
    {
        $base = $this->base();
        /** @var User $user */
        $user = $base['user'];

        $pengajuanPenjual = $user
            ? PengajuanPenjual::where('user_id', $user->id)->latest()->first()
            : null;

        $produkSaya = ($pengajuanPenjual?->status === 'disetujui')
            ? Produk::where('user_id', $user->id)->latest()->get()
            : collect();

        return view('mobile.produk', array_merge($base, [
            'pengajuanPenjual' => $pengajuanPenjual,
            'produkSaya'       => $produkSaya,
        ]));
    }

    public function prediksiStunting()
    {
        $base = $this->base();
        /** @var User $user */
        $user = $base['user'];

        $riwayat = AiRiwayat::where('user_id', $user->id)
            ->where('tipe', 'prediksi-stunting')
            ->latest()
            ->take(10)
            ->get();

        return view('mobile.prediksi-stunting', array_merge($base, compact('riwayat')));
    }

    public function prediksiStuntingGenerate(Request $request): JsonResponse
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'umur_bulan'    => 'required|integer|min:0|max:60',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan'   => 'required|numeric|min:0.1|max:50',
            'tinggi_badan'  => 'required|numeric|min:1|max:150',
        ]);

        [$system, $userPrompt] = $this->buildStuntingPrompt($request);
        $result = $this->callAi($system, $userPrompt);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        $parsed = $this->extractJsonFromText($result['content']);
        if (! $parsed) {
            return response()->json([
                'error' => 'AI tidak mengembalikan format JSON yang valid. Coba ulangi analisis.',
            ], 500);
        }

        $saved = AiRiwayat::create([
            'user_id'   => Auth::id(),
            'tipe'      => 'prediksi-stunting',
            'parameter' => $request->except(['_token']),
            'konten'    => $result['content'],
        ]);

        return response()->json([
            'parsed'  => $parsed,
            'content' => $result['content'],
            'riwayat' => [
                'id'         => $saved->id,
                'created_at' => $saved->created_at->toISOString(),
                'parameter'  => $saved->parameter,
            ],
        ]);
    }

    private function buildStuntingPrompt(Request $request): array
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

        $userMsg = "Data balita:\n"
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

        return [$system, $userMsg];
    }

    private function extractJsonFromText(string $text): ?array
    {
        $text = preg_replace('/^```(?:json)?\s*/im', '', $text);
        $text = preg_replace('/```\s*$/im', '', $text);
        $text = trim($text);

        $decoded = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

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

    private function callAi(string $system, string $userMsg): array
    {
        $active = Setting::get('api.active') ?? 'openai';
        $model  = Setting::get("api.{$active}.model");
        $key    = Setting::get("api.{$active}.key");

        if (! $key) {
            $key   = Setting::get('api.key');
            $model = $model ?: Setting::get('api.model') ?? 'gpt-4o-mini';
        }

        if (! $key) {
            return ['error' => 'Kunci API belum dikonfigurasi.'];
        }

        $model       = $model ?: 'gpt-4o-mini';
        $isAnthropic = $active === 'anthropic' || str_starts_with($model, 'claude');

        try {
            if ($isAnthropic) {
                $response = Http::timeout(120)
                    ->withHeaders([
                        'x-api-key'         => $key,
                        'anthropic-version' => '2023-06-01',
                        'content-type'      => 'application/json',
                    ])
                    ->post('https://api.anthropic.com/v1/messages', [
                        'model'      => $model,
                        'max_tokens' => 1200,
                        'system'     => $system,
                        'messages'   => [['role' => 'user', 'content' => $userMsg]],
                    ]);

                if ($response->successful()) {
                    return ['content' => $response->json('content.0.text') ?? ''];
                }
                $err = $response->json('error.message') ?? $response->body();
                return ['error' => "Anthropic API error: {$err}"];
            }

            $baseUrl  = ($active === 'custom') ? Setting::get('api.custom.url') : null;
            $endpoint = rtrim($baseUrl ?: 'https://api.openai.com', '/') . '/v1/chat/completions';

            $response = Http::timeout(120)
                ->withToken($key)
                ->post($endpoint, [
                    'model'      => $model,
                    'max_tokens' => 1200,
                    'messages'   => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user',   'content' => $userMsg],
                    ],
                ]);

            if ($response->successful()) {
                return ['content' => $response->json('choices.0.message.content') ?? ''];
            }
            $err = $response->json('error.message') ?? $response->body();
            return ['error' => "OpenAI API error: {$err}"];
        } catch (\Exception $e) {
            return ['error' => 'Gagal menghubungi API: ' . $e->getMessage()];
        }
    }

    public function notifikasi()
    {
        $user   = $this->user();
        $notifs = $user->notifications()->latest()->paginate(20);
        $user->unreadNotifications()->update(['read_at' => now()]);

        return view('mobile.notifikasi', array_merge($this->base(), compact('notifs')));
    }

    private function user(): \App\Models\User
    {
        /** @var \App\Models\User */
        return \Illuminate\Support\Facades\Auth::user();
    }
}
