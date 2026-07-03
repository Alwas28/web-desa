<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $query = LaporanWarga::with('user')
            ->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $laporan   = $query->paginate(20)->withQueryString();
        $counts    = [
            'semua'    => LaporanWarga::count(),
            'menunggu' => LaporanWarga::where('status', 'menunggu')->count(),
            'diproses' => LaporanWarga::where('status', 'diproses')->count(),
            'selesai'  => LaporanWarga::where('status', 'selesai')->count(),
        ];

        return view('admin.laporan.index', compact('laporan', 'counts', 'status'));
    }

    public function show(LaporanWarga $laporan)
    {
        $laporan->load('user.penduduk', 'pembalas');

        return view('admin.laporan.show', compact('laporan'));
    }

    public function update(Request $request, LaporanWarga $laporan)
    {
        $request->validate([
            'status'                => 'required|in:menunggu,diproses,selesai',
            'balasan'               => 'nullable|string|max:2000',
            'rencana_tindak_lanjut' => 'nullable|string|max:3000',
            'laporan_penanganan'    => 'nullable|string|max:3000',
        ]);

        $data = [
            'status'                => $request->status,
            'balasan'               => $request->balasan,
            'rencana_tindak_lanjut' => $request->rencana_tindak_lanjut,
            'laporan_penanganan'    => $request->laporan_penanganan,
        ];

        if ($request->balasan && ! $laporan->dibalas_at) {
            $data['dibalas_oleh'] = Auth::id();
            $data['dibalas_at']   = now();
        }

        if ($request->status === 'selesai' && ! $laporan->ditangani_at) {
            $data['ditangani_at'] = now();
        }

        if ($request->status !== 'selesai') {
            $data['ditangani_at']    = null;
            $data['laporan_penanganan'] = $request->laporan_penanganan;
        }

        $laporan->update($data);

        return back()->with('toast_success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(LaporanWarga $laporan)
    {
        $laporan->delete();

        return redirect()->route('admin.laporan.index')
            ->with('toast_success', 'Laporan berhasil dihapus.');
    }
}
