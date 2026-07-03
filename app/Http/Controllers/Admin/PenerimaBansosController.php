<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisBansos;
use App\Models\Penduduk;
use App\Models\PenerimaBansos;
use App\Models\PenyaluranBansos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaBansosController extends Controller
{
    public function index(Request $request)
    {
        $jenisList = JenisBansos::where('aktif', true)->orderBy('nama')->get();

        $q = PenerimaBansos::with('jenisBansos')
            ->when($request->filled('jenis'), fn($query) => $query->where('jenis_bansos_id', $request->jenis))
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->when($request->filled('tahun'), fn($query) => $query->where('tahun_ditetapkan', $request->tahun))
            ->when($request->filled('cari'), fn($query) => $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('nik', 'like', '%' . $request->cari . '%')
                  ->orWhere('no_kk', 'like', '%' . $request->cari . '%');
            }))
            ->latest();

        $penerima = $q->paginate(20)->withQueryString();

        $stats = [
            'total'    => PenerimaBansos::count(),
            'aktif'    => PenerimaBansos::where('status', 'aktif')->count(),
            'menunggu' => PenerimaBansos::where('status', 'menunggu')->count(),
            'nonaktif' => PenerimaBansos::where('status', 'nonaktif')->count(),
        ];

        return view('admin.bansos.index', compact('penerima', 'jenisList', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_bansos_id'  => 'required|exists:jenis_bansos,id',
            'penduduk_id'      => 'nullable|exists:penduduks,id',
            'nama'             => 'required|string|max:150',
            'nik'              => 'nullable|string|max:16',
            'no_kk'            => 'nullable|string|max:16',
            'alamat'           => 'nullable|string',
            'rt'               => 'nullable|string|max:5',
            'rw'               => 'nullable|string|max:5',
            'tahun_ditetapkan' => 'nullable|digits:4|integer|min:2000|max:2100',
            'status'            => 'required|in:menunggu,aktif,nonaktif',
            'jenis_penerimaan'  => 'required|in:tunai,non_tunai',
            'keterangan'        => 'nullable|string',
        ]);

        PenerimaBansos::create($data);
        return back()->with('success', 'Penerima bansos berhasil ditambahkan.');
    }

    public function show(PenerimaBansos $penerimaBanso)
    {
        $penerimaBanso->load(['jenisBansos', 'penduduk', 'penyaluran.pencatat']);

        $stats = [
            'total_penyaluran'  => $penerimaBanso->penyaluran->count(),
            'total_nilai'       => $penerimaBanso->penyaluran->where('status', 'disalurkan')->sum('nilai_disalurkan'),
            'terakhir'          => $penerimaBanso->penyaluran->sortByDesc('tanggal_penyaluran')->first(),
        ];

        return view('admin.bansos.show', compact('penerimaBanso', 'stats'));
    }

    public function update(Request $request, PenerimaBansos $penerimaBanso)
    {
        $data = $request->validate([
            'jenis_bansos_id'  => 'required|exists:jenis_bansos,id',
            'penduduk_id'      => 'nullable|exists:penduduks,id',
            'nama'             => 'required|string|max:150',
            'nik'              => 'nullable|string|max:16',
            'no_kk'            => 'nullable|string|max:16',
            'alamat'           => 'nullable|string',
            'rt'               => 'nullable|string|max:5',
            'rw'               => 'nullable|string|max:5',
            'tahun_ditetapkan' => 'nullable|digits:4|integer|min:2000|max:2100',
            'status'            => 'required|in:menunggu,aktif,nonaktif',
            'jenis_penerimaan'  => 'required|in:tunai,non_tunai',
            'keterangan'        => 'nullable|string',
        ]);

        $penerimaBanso->update($data);
        return back()->with('success', 'Data penerima berhasil diperbarui.');
    }

    public function destroy(PenerimaBansos $penerimaBanso)
    {
        $penerimaBanso->delete();
        return redirect()->route('admin.bansos.index')->with('success', 'Data penerima berhasil dihapus.');
    }

    // ── Penyaluran ────────────────────────────────────────────────────────────────

    public function salurkan(Request $request, PenerimaBansos $penerimaBanso)
    {
        $data = $request->validate([
            'periode'           => 'required|string|max:60',
            'tanggal_penyaluran'=> 'nullable|date',
            'nilai_disalurkan'  => 'nullable|numeric|min:0',
            'status'            => 'required|in:terjadwal,disalurkan,tidak_hadir,ditolak',
            'keterangan'        => 'nullable|string',
        ]);

        $data['penerima_bansos_id'] = $penerimaBanso->id;
        $data['dicatat_oleh']       = Auth::id();

        PenyaluranBansos::create($data);
        return back()->with('success', 'Penyaluran berhasil dicatat.');
    }

    public function updatePenyaluran(Request $request, PenerimaBansos $penerimaBanso, PenyaluranBansos $penyaluran)
    {
        $data = $request->validate([
            'periode'           => 'required|string|max:60',
            'tanggal_penyaluran'=> 'nullable|date',
            'nilai_disalurkan'  => 'nullable|numeric|min:0',
            'status'            => 'required|in:terjadwal,disalurkan,tidak_hadir,ditolak',
            'keterangan'        => 'nullable|string',
        ]);

        $penyaluran->update($data);
        return back()->with('success', 'Penyaluran berhasil diperbarui.');
    }

    public function destroyPenyaluran(PenerimaBansos $penerimaBanso, PenyaluranBansos $penyaluran)
    {
        $penyaluran->delete();
        return back()->with('success', 'Data penyaluran berhasil dihapus.');
    }

    public function cariPenduduk(Request $request)
    {
        $q = $request->get('q', '');
        $results = Penduduk::where('nik', 'like', "%{$q}%")
            ->orWhere('nama_lengkap', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'nik', 'nama_lengkap', 'alamat']);

        return response()->json($results->map(fn($p) => [
            'id'    => $p->id,
            'nik'   => $p->nik,
            'nama'  => $p->nama_lengkap,
            'alamat'=> $p->alamat ?? '',
        ]));
    }
}
