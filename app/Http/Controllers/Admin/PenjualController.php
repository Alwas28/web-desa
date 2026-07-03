<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPenjual;
use App\Models\User;
use App\Notifications\StatusPenjualUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualController extends Controller
{
    public function index(Request $request)
    {
        $q = PengajuanPenjual::with('user', 'penduduk', 'ditinjauOleh')->latest();

        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }

        $pengajuans  = $q->paginate(20)->withQueryString();
        $totalSemua  = PengajuanPenjual::count();
        $totalTunggu = PengajuanPenjual::where('status', 'menunggu')->count();
        $totalSetuju = PengajuanPenjual::where('status', 'disetujui')->count();
        $totalTolak  = PengajuanPenjual::where('status', 'ditolak')->count();

        return view('admin.penjual.index', compact(
            'pengajuans', 'totalSemua', 'totalTunggu', 'totalSetuju', 'totalTolak'
        ));
    }

    public function setujui(Request $request, PengajuanPenjual $pengajuan)
    {
        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status'       => 'disetujui',
            'catatan_admin' => $request->input('catatan'),
            'ditinjau_oleh' => Auth::id(),
            'ditinjau_at'   => now(),
        ]);

        $pengajuan->user->notify(new StatusPenjualUpdated($pengajuan->fresh()));

        return back()->with('success', 'Pengajuan disetujui. Warga telah dinotifikasi.');
    }

    public function tolak(Request $request, PengajuanPenjual $pengajuan)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi agar warga dapat memperbaiki pengajuannya.',
        ]);

        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $pengajuan->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan,
            'ditinjau_oleh' => Auth::id(),
            'ditinjau_at'   => now(),
        ]);

        $pengajuan->user->notify(new StatusPenjualUpdated($pengajuan->fresh()));

        return back()->with('success', 'Pengajuan ditolak. Warga telah dinotifikasi.');
    }
}
