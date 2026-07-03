<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratDomisili;
use Illuminate\Http\Request;

class SuratDomisiliController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratDomisili::with('diprosesOleh')->latest();

        if ($q = $request->input('q')) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_pemohon', 'like', "%{$q}%")
                  ->orWhere('nik', 'like', "%{$q}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return view('admin.surat.domisili.index', [
            'surats'    => $query->paginate(20)->withQueryString(),
            'pending'   => SuratDomisili::where('status', 'pending')->count(),
            'diproses'  => SuratDomisili::where('status', 'diproses')->count(),
            'selesai'   => SuratDomisili::where('status', 'selesai')->count(),
            'ditolak'   => SuratDomisili::where('status', 'ditolak')->count(),
            'total'     => SuratDomisili::count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pemohon' => 'required|string|max:150',
            'nik'          => 'required|string|size:16',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir'=> 'required|date',
            'jenis_kelamin'=> 'required|in:L,P',
            'agama'        => 'required|string|max:50',
            'pekerjaan'    => 'required|string|max:100',
            'alamat'       => 'required|string',
            'rt'           => 'nullable|string|max:5',
            'rw'           => 'nullable|string|max:5',
            'keperluan'    => 'required|string',
        ]);

        SuratDomisili::create($data);

        return back()->with('success', 'Pengajuan surat berhasil ditambahkan.');
    }

    public function update(Request $request, SuratDomisili $suratDomisili)
    {
        $data = $request->validate([
            'status'      => 'required|in:pending,diproses,selesai,ditolak',
            'nomor_surat' => 'nullable|string|max:50',
            'catatan'     => 'nullable|string|max:1000',
        ]);

        $data['diproses_oleh']   = auth()->id();
        $data['tanggal_selesai'] = in_array($data['status'], ['selesai', 'ditolak']) ? now() : null;

        $suratDomisili->update($data);

        return back()->with('success', 'Status surat berhasil diperbarui.');
    }

    public function destroy(SuratDomisili $suratDomisili)
    {
        $suratDomisili->delete();
        return back()->with('success', 'Pengajuan surat berhasil dihapus.');
    }
}
