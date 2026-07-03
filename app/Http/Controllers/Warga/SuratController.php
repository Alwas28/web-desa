<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanSuratBaru;
use App\Services\SuratPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SuratController extends Controller
{
    /** Buat pengajuan surat baru */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->penduduk_id) {
            return redirect()->route('portal')->with('error', 'Akun Anda belum terhubung dengan data penduduk.');
        }

        $pengaju = Penduduk::find($user->penduduk_id);

        $request->validate([
            'jenis_surat_id'     => ['required', 'exists:jenis_surats,id'],
            'untuk_penduduk_id'  => ['nullable', 'exists:penduduks,id'],
            'keperluan'          => ['required', 'string', 'max:500'],
            'keterangan'         => ['nullable', 'string', 'max:1000'],
        ], [
            'jenis_surat_id.required' => 'Pilih jenis surat terlebih dahulu.',
            'keperluan.required'      => 'Keperluan tidak boleh kosong.',
        ]);

        $jenis = JenisSurat::findOrFail($request->jenis_surat_id);

        // Tentukan penerima surat
        if ($pengaju?->isKepalaKeluarga() && $request->filled('untuk_penduduk_id')) {
            // KK head mengajukan untuk anggota keluarga — pastikan satu KK
            $penerima = Penduduk::where('kk_id', $pengaju->kk_id)
                ->where('id', $request->untuk_penduduk_id)
                ->firstOrFail();
        } else {
            // Anggota keluarga biasa — hanya untuk diri sendiri
            $penerima = $pengaju;
        }

        if (! $penerima) {
            return redirect()->route('portal.layanan')
                ->with('error', 'Data penduduk tidak ditemukan.');
        }

        // Blok 1 pengajuan per hari per jenis surat per penerima, kecuali yang ditolak
        $blockedToday = PengajuanSurat::where('penduduk_id', $penerima->id)
            ->where('jenis_surat', $jenis->nama)
            ->whereDate('created_at', today())
            ->where('status', '!=', 'ditolak')
            ->exists();

        if ($blockedToday) {
            $namaPenerima = $penerima->id === $pengaju?->id ? 'Anda' : $penerima->nama_lengkap;
            return redirect()->route('portal.layanan')
                ->with('error', $jenis->nama . ' untuk ' . $namaPenerima . ' sudah diajukan hari ini.');
        }

        $pengajuan = PengajuanSurat::create([
            'penduduk_id'               => $penerima->id,
            'diajukan_oleh_penduduk_id' => $user->penduduk_id,
            'jenis_surat'               => $jenis->nama,
            'keperluan'                 => $request->keperluan,
            'keterangan'                => $request->keterangan,
            'status'                    => 'diajukan',
        ]);

        // Kirim notifikasi ke semua admin (user yang punya role)
        $pengajuan->load('penduduk');
        $admins = User::whereHas('roles')->get();
        Notification::send($admins, new PengajuanSuratBaru($pengajuan));

        $namaPenerima = $penerima->id === $pengaju?->id ? '' : ' a.n. ' . $penerima->nama_lengkap;
        return redirect()->route('portal.layanan')
            ->with('success', 'Pengajuan ' . $jenis->nama . $namaPenerima . ' berhasil dikirim! Pantau status di tab Layanan.');
    }

    /** Download PDF surat yang sudah selesai */
    public function downloadPdf(int $id)
    {
        $user      = Auth::user();
        $pengajuan = PengajuanSurat::findOrFail($id);

        // Izinkan pemilik surat ATAU KK head yang mengajukan
        $pengaju = Penduduk::find($user->penduduk_id);
        $isKKMember = $pengaju?->isKepalaKeluarga()
            && Penduduk::where('kk_id', $pengaju->kk_id)
                       ->where('id', $pengajuan->penduduk_id)
                       ->exists();

        if ($pengajuan->penduduk_id !== $user->penduduk_id && ! $isKKMember) {
            abort(403);
        }
        if ($pengajuan->status !== 'selesai') {
            abort(404, 'Surat belum selesai diproses.');
        }

        return SuratPdfService::download($pengajuan);
    }
}
