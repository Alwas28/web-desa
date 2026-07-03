<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriArsip;
use App\Models\NavItem;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $logoPath = Setting::get('desa.logo');

        $navParents = NavItem::whereNull('parent_id')->orderBy('urutan')
            ->with(['children.page', 'children.kategoriArsip', 'page', 'kategoriArsip'])
            ->get();

        return view('admin.pengaturan', [
            'settingsDesa'   => Setting::forGroup('desa.'),
            'settingsApi'    => Setting::forGroup('api.'),
            'settingsFitur'  => Setting::forGroup('publik.'),
            'settingsHeader' => Setting::forGroup('header.'),
            'apiKeyExists'   => Setting::where('key', 'api.key')->exists(),
            'apiKeysExist'   => [
                'anthropic' => Setting::where('key', 'api.anthropic.key')->exists(),
                'openai'    => Setting::where('key', 'api.openai.key')->exists(),
                'google'    => Setting::where('key', 'api.google.key')->exists(),
                'custom'    => Setting::where('key', 'api.custom.key')->exists(),
            ],
            'logoUrl'        => $logoPath ? Storage::url($logoPath) : null,
            'navParents'     => $navParents,
            'pagesForNav'    => Page::orderBy('judul')->get(['id', 'judul', 'slug']),
            'kategoriArsips' => KategoriArsip::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    /** Simpan identitas desa. */
    public function updateDesa(Request $request): JsonResponse
    {
        $request->validate([
            'nama'       => 'nullable|string|max:150',
            'kecamatan'  => 'nullable|string|max:100',
            'kabupaten'  => 'nullable|string|max:100',
            'provinsi'   => 'nullable|string|max:100',
            'kode_pos'   => 'nullable|string|max:10',
            'kepala'     => 'nullable|string|max:150',
            'sekretaris' => 'nullable|string|max:150',
            'alamat'     => 'nullable|string|max:500',
            'whatsapp'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:150',
            'logo'       => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'hapus_logo' => 'nullable',
            'idm_status' => 'nullable|in:Sangat Tertinggal,Tertinggal,Berkembang,Maju,Mandiri',
            'idm_skor'   => 'nullable|numeric|min:0|max:1',
            'idm_tahun'  => 'nullable|digits:4|integer|min:2000|max:2099',
        ]);

        foreach ($request->only(['nama','kecamatan','kabupaten','provinsi','kode_pos','kepala','sekretaris','alamat','whatsapp','email','idm_status','idm_skor','idm_tahun']) as $field => $val) {
            Setting::set("desa.$field", $val);
        }

        $logoUrl = null;

        if ($request->hasFile('logo')) {
            $old = Setting::get('desa.logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('logo')->store('desa', 'public');
            Setting::set('desa.logo', $path);
            $logoUrl = Storage::url($path);
        } elseif ($request->input('hapus_logo') === '1') {
            $old = Setting::get('desa.logo');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('desa.logo', null);
        }

        return response()->json([
            'message'  => 'Identitas desa berhasil disimpan.',
            'logo_url' => $logoUrl,
        ]);
    }

    /** Simpan pengaturan API multi-provider (token dienkripsi). */
    public function updateApi(Request $request): JsonResponse
    {
        $request->validate([
            'active'          => 'nullable|in:anthropic,openai,google,custom',
            'limit'           => 'nullable|string|max:50',
            'anthropic.key'   => 'nullable|string|max:600',
            'anthropic.model' => 'nullable|string|max:100',
            'openai.key'      => 'nullable|string|max:600',
            'openai.model'    => 'nullable|string|max:100',
            'google.key'      => 'nullable|string|max:600',
            'google.model'    => 'nullable|string|max:100',
            'custom.label'    => 'nullable|string|max:100',
            'custom.url'      => 'nullable|string|max:500',
            'custom.key'      => 'nullable|string|max:600',
            'custom.secret'   => 'nullable|string|max:600',
            'custom.model'    => 'nullable|string|max:100',
        ]);

        Setting::set('api.active', $request->input('active', 'openai'));
        Setting::set('api.limit',  $request->input('limit'));

        foreach (['anthropic', 'openai', 'google'] as $provider) {
            Setting::set("api.{$provider}.model", $request->input("{$provider}.model"));
            if ($request->filled("{$provider}.key")) {
                Setting::set("api.{$provider}.key", $request->input("{$provider}.key"), true);
            }
        }

        Setting::set('api.custom.label', $request->input('custom.label'));
        Setting::set('api.custom.url',   $request->input('custom.url'));
        Setting::set('api.custom.model', $request->input('custom.model'));
        if ($request->filled('custom.key')) {
            Setting::set('api.custom.key', $request->input('custom.key'), true);
        }
        if ($request->filled('custom.secret')) {
            Setting::set('api.custom.secret', $request->input('custom.secret'), true);
        }

        return response()->json(['message' => 'Pengaturan API berhasil disimpan.']);
    }

    /** Simpan pengaturan header / hero homepage. */
    public function updateHeader(Request $request): JsonResponse
    {
        $request->validate([
            'tipe'           => 'required|in:biasa,slide,video',
            'judul'          => 'nullable|string|max:200',
            'subjudul'       => 'nullable|string|max:500',
            'badge'           => 'nullable|string|max:150',
            'btn_layanan'     => 'nullable|string|max:80',
            'btn_layanan_url' => 'nullable|string|max:500',
            'btn_profil'      => 'nullable|string|max:80',
            'btn_profil_url'  => 'nullable|string|max:500',
            'video_url'      => 'nullable|string|max:500',
            'video_mode'     => 'nullable|in:url,upload',
            'slides_json'    => 'nullable|string',
            'slide_photo.*'  => 'nullable|image|max:5120',
            'bg_foto'        => 'nullable|image|max:5120',
            'bg_video'       => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
            'hapus_bg_foto'  => 'nullable',
            'hapus_bg_video' => 'nullable',
        ]);

        Setting::set('header.tipe',        $request->input('tipe', 'biasa'));
        Setting::set('header.judul',       $request->input('judul'));
        Setting::set('header.subjudul',    $request->input('subjudul'));
        Setting::set('header.badge',           $request->input('badge'));
        Setting::set('header.btn_layanan',     $request->input('btn_layanan'));
        Setting::set('header.btn_layanan_url', $request->input('btn_layanan_url'));
        Setting::set('header.btn_profil',      $request->input('btn_profil'));
        Setting::set('header.btn_profil_url',  $request->input('btn_profil_url'));

        // Video URL hanya disimpan jika mode URL
        if ($request->input('video_mode') === 'url') {
            Setting::set('header.video_url', $request->input('video_url'));
        }

        // Foto background (header biasa)
        if ($request->hasFile('bg_foto')) {
            $old = Setting::get('header.bg_foto');
            if ($old) Storage::disk('public')->delete($old);
            $bgPath = $request->file('bg_foto')->store('header', 'public');
            Setting::set('header.bg_foto', $bgPath);
        } elseif ($request->input('hapus_bg_foto') === '1') {
            $old = Setting::get('header.bg_foto');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('header.bg_foto', null);
        }

        // Video upload (header video, mode upload)
        if ($request->hasFile('bg_video')) {
            $old = Setting::get('header.bg_video');
            if ($old) Storage::disk('public')->delete($old);
            $videoPath = $request->file('bg_video')->store('header/video', 'public');
            Setting::set('header.bg_video', $videoPath);
            Setting::set('header.video_url', null);
        } elseif ($request->input('hapus_bg_video') === '1') {
            $old = Setting::get('header.bg_video');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('header.bg_video', null);
        }

        // Slides — akses file array langsung agar lebih reliable
        $slidesJson  = $request->input('slides_json', '[]');
        $metas       = json_decode($slidesJson, true) ?: [];
        $existing    = json_decode(Setting::get('header.slides', '[]'), true) ?: [];
        $slideFiles  = $request->file('slide_photo') ?: [];

        $result = [];
        foreach ($metas as $i => $meta) {
            $path = $meta['path'] ?? null;

            if (isset($slideFiles[$i]) && $slideFiles[$i]->isValid()) {
                if ($path) Storage::disk('public')->delete($path);
                $path = $slideFiles[$i]->store('header/slides', 'public');
            }

            if ($path) {
                $result[] = [
                    'path'     => $path,
                    'judul'    => $meta['judul']    ?? '',
                    'subjudul' => $meta['subjudul'] ?? '',
                ];
            }
        }

        // Hapus slide yang dihilangkan
        $keptPaths = array_column($result, 'path');
        foreach ($existing as $old) {
            if (!empty($old['path']) && !in_array($old['path'], $keptPaths)) {
                Storage::disk('public')->delete($old['path']);
            }
        }

        Setting::set('header.slides', json_encode($result));

        return response()->json(['message' => 'Pengaturan header berhasil disimpan.']);
    }

    /** Simpan profil desa: sambutan, visi, misi. */
    public function updateProfil(Request $request): JsonResponse
    {
        $request->validate([
            'sambutan' => 'nullable|string|max:3000',
            'visi'     => 'nullable|string|max:1000',
            'misi'     => 'nullable|string|max:3000',
        ]);

        Setting::set('desa.sambutan', $request->input('sambutan'));
        Setting::set('desa.visi',     $request->input('visi'));
        Setting::set('desa.misi',     $request->input('misi'));

        return response()->json(['message' => 'Profil desa berhasil disimpan.']);
    }

    /** Simpan pengaturan fitur publik. */
    public function updateFitur(Request $request): JsonResponse
    {
        $request->validate([
            'grafik_demografi' => 'nullable|boolean',
            'grafik_apbdes'    => 'nullable|boolean',
            'grafik_surat'     => 'nullable|boolean',
            'grafik_konten'    => 'nullable|boolean',
        ]);

        Setting::set('publik.grafik.demografi', $request->boolean('grafik_demografi') ? '1' : '0');
        Setting::set('publik.grafik.apbdes',    $request->boolean('grafik_apbdes')    ? '1' : '0');
        Setting::set('publik.grafik.surat',     $request->boolean('grafik_surat')     ? '1' : '0');
        Setting::set('publik.grafik.konten',    $request->boolean('grafik_konten')    ? '1' : '0');

        return response()->json(['message' => 'Pengaturan fitur berhasil disimpan.']);
    }
}
