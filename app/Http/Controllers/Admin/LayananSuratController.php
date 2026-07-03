<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\Pejabat;
use App\Models\Periode;
use App\Models\PengajuanSurat;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\StatusSuratUpdated;
use App\Services\SuratPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LayananSuratController extends Controller
{
    private function kadesAktif(): ?Pejabat
    {
        $periodeId = Periode::where(function ($q) {
            $q->whereNull('selesai')->orWhere('selesai', '>=', today());
        })->latest('mulai')->value('id');

        if (! $periodeId) return null;

        return Pejabat::where('periode_id', $periodeId)
            ->whereHas('jabatan', fn ($q) => $q
                ->where('nama', 'like', '%kepala desa%')
                ->orWhere('singkatan', 'like', '%kades%'))
            ->with('jabatan')
            ->first();
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->hasPermission('lihat.layanan-surat')) abort(403);

        $q = PengajuanSurat::with('penduduk')->latest();

        if ($s = $request->input('q')) {
            $q->whereHas('penduduk', fn ($w) =>
                $w->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%")
            );
        }
        if ($status = $request->input('status')) $q->where('status', $status);
        if ($jenis  = $request->input('jenis'))  $q->where('jenis_surat', $jenis);

        $surats = $q->paginate(25)->withQueryString();
        $kades  = $this->kadesAktif();

        $counts = PengajuanSurat::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $counts = array_merge(
            ['diajukan'=>0,'menunggu_ttd'=>0,'disetujui'=>0,'selesai'=>0,'ditolak'=>0],
            $counts
        );
        $counts['total'] = array_sum($counts);

        $kop       = Setting::forGroup('surat.kop_');
        $jenisList = JenisSurat::aktif()->orderBy('urutan')->orderBy('nama')->get();

        return view('admin.surat.layanan.index', [
            'surats'     => $surats,
            'kades'      => $kades,
            'counts'     => $counts,
            'kop'        => $kop,
            'jenisList'  => $jenisList,
            'canProses'  => $user->hasPermission('proses.layanan-surat'),
            'canSetujui' => $user->hasPermission('setujui.layanan-surat'),
            'canBuat'    => $user->hasPermission('buat.layanan-surat'),
            'canHapus'   => $user->hasPermission('hapus.layanan-surat'),
        ]);
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->hasPermission('buat.layanan-surat')) abort(403);

        $data = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'jenis_surat' => 'required|string|max:100',
            'keperluan'   => 'required|string',
            'keterangan'  => 'nullable|string',
        ]);

        PengajuanSurat::create($data + ['status' => 'diajukan']);

        return redirect()->route('admin.layanan-surat.index')
            ->with('success', 'Pengajuan surat berhasil ditambahkan.');
    }

    public function update(Request $request, PengajuanSurat $layananSurat)
    {
        /** @var User $user */
        $user      = Auth::user();
        $newStatus = $request->input('status');

        // Gate per transisi
        if (in_array($newStatus, ['menunggu_ttd', 'selesai'])
            && ! $user->hasPermission('proses.layanan-surat')) {
            abort(403, 'Anda tidak memiliki izin memproses surat ini.');
        }
        if ($newStatus === 'disetujui' && ! $user->hasPermission('setujui.layanan-surat')) {
            abort(403, 'Hanya Kepala Desa yang dapat menyetujui surat ini.');
        }
        if ($newStatus === 'ditolak'
            && ! $user->hasPermission('proses.layanan-surat')
            && ! $user->hasPermission('setujui.layanan-surat')) {
            abort(403);
        }

        $validated = $request->validate([
            'status'      => 'required|in:diajukan,menunggu_ttd,disetujui,selesai,ditolak',
            'nomor_surat' => 'nullable|required_if:status,selesai|string|max:80',
            'catatan'     => 'nullable|string',
        ]);

        $update = [
            'status'  => $validated['status'],
            'catatan' => $validated['catatan'] ?? $layananSurat->catatan,
        ];

        if ($newStatus === 'disetujui') {
            $kades = $this->kadesAktif();
            $update['disetujui_oleh'] = $user->id;
            $update['nama_kades']     = $kades?->nama;
            $update['jabatan_kades']  = $kades?->jabatan?->nama ?? 'Kepala Desa';
        }

        if ($newStatus === 'selesai') {
            $update['nomor_surat']     = $validated['nomor_surat'];
            $update['tanggal_selesai'] = now();
        }

        if ($newStatus === 'ditolak') {
            $update['tanggal_selesai'] = now();
        }

        $layananSurat->update($update);

        // Kirim notifikasi ke warga pemilik pengajuan
        $wargaUser = User::where('penduduk_id', $layananSurat->penduduk_id)->first();
        if ($wargaUser) {
            $wargaUser->notify(new StatusSuratUpdated($layananSurat->fresh()));
        }

        return redirect()->route('admin.layanan-surat.index')
            ->with('success', 'Status surat berhasil diperbarui.');
    }

    public function destroy(PengajuanSurat $layananSurat)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->hasPermission('hapus.layanan-surat')) abort(403);
        $layananSurat->delete();
        return redirect()->route('admin.layanan-surat.index')
            ->with('success', 'Pengajuan surat berhasil dihapus.');
    }

    public function downloadPdf(PengajuanSurat $layananSurat)
    {
        if ($layananSurat->status !== 'selesai') {
            return back()->with('error', 'Surat belum berstatus selesai.');
        }
        return SuratPdfService::download($layananSurat);
    }

    public function nomorOtomatis(PengajuanSurat $layananSurat)
    {
        return response()->json(['nomor' => $layananSurat->generateNomorSurat()]);
    }

    public function verifikasi(string $token)
    {
        $surat       = PengajuanSurat::where('token', $token)->with('penduduk')->firstOrFail();
        $desa        = Setting::forGroup('desa.');
        $kopTemplate = Setting::get('surat.kop_template', SuratPengaturanController::DEFAULT_TEMPLATE);
        $kopRendered = SuratPengaturanController::renderTemplate($kopTemplate, $desa);
        $kopAlign    = Setting::get('surat.kop_align', 'center');
        $logoPos     = Setting::get('surat.logo_position', 'above');
        $kopFont     = Setting::get('surat.kop_font', 'Inter');
        $kopSizes    = [
            'l1' => (int) Setting::get('surat.kop_size_l1', 11),
            'l2' => (int) Setting::get('surat.kop_size_l2', 18),
            'l3' => (int) Setting::get('surat.kop_size_l3', 11),
            'l4' => (int) Setting::get('surat.kop_size_l4', 11),
            'l5' => (int) Setting::get('surat.kop_size_l5', 11),
        ];
        $logoPath = $desa['desa.logo'] ?? null;
        $logoUrl  = $logoPath ? \Illuminate\Support\Facades\Storage::url($logoPath) : null;

        return view('verifikasi-surat', compact(
            'surat', 'kopRendered', 'kopAlign', 'logoPos', 'kopFont', 'kopSizes', 'logoUrl'
        ));
    }
}
