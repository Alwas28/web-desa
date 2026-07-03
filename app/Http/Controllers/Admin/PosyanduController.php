<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\IbuHamil;
use App\Models\KegiatanPosyandu;
use App\Models\Posyandu;
use App\Models\RekamKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PosyanduController extends Controller
{
    public function index()
    {
        return view('admin.kesehatan.posyandu', [
            'posyandus' => Posyandu::orderBy('nama')->get(),
        ]);
    }

    public function indexKegiatan(Request $request)
    {
        $posyandus = Posyandu::orderBy('nama')->get();

        $kegiatans = KegiatanPosyandu::with('posyandu')
            ->withCount('rekams')
            ->orderByDesc('tanggal')
            ->get();

        return view('admin.kesehatan.kegiatan', compact('kegiatans', 'posyandus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'dusun'      => 'nullable|string|max:100',
            'lokasi'     => 'nullable|string|max:300',
            'kader'      => 'nullable|string|max:200',
            'jadwal'     => 'nullable|string|max:200',
            'aktif'      => 'nullable|boolean',
            'keterangan' => 'nullable|string|max:1000',
        ]);
        $data['aktif'] = $request->boolean('aktif', true);

        Posyandu::create($data);

        return back()->with('success', 'Posyandu "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Posyandu $posyandu): RedirectResponse
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'dusun'      => 'nullable|string|max:100',
            'lokasi'     => 'nullable|string|max:300',
            'kader'      => 'nullable|string|max:200',
            'jadwal'     => 'nullable|string|max:200',
            'aktif'      => 'nullable|boolean',
            'keterangan' => 'nullable|string|max:1000',
        ]);
        $data['aktif'] = $request->boolean('aktif', true);

        $posyandu->update($data);

        return back()->with('success', 'Posyandu berhasil diperbarui.');
    }

    public function destroy(Posyandu $posyandu): RedirectResponse
    {
        $posyandu->delete();
        return back()->with('success', 'Posyandu berhasil dihapus.');
    }

    public function storeKegiatan(Request $request, Posyandu $posyandu): RedirectResponse
    {
        $data = $request->validate([
            'tanggal'       => 'required|date|before_or_equal:today',
            'jumlah_balita' => 'nullable|integer|min:0|max:9999',
            'jumlah_bumil'  => 'nullable|integer|min:0|max:9999',
            'jumlah_lansia' => 'nullable|integer|min:0|max:9999',
            'petugas'       => 'nullable|string|max:200',
            'keterangan'    => 'nullable|string|max:1000',
        ]);
        $data['posyandu_id'] = $posyandu->id;

        $kegiatan = KegiatanPosyandu::create($data);

        return redirect()->route('admin.kesehatan.posyandu.kegiatan.show', [$posyandu, $kegiatan])
            ->with('success', 'Sesi kegiatan berhasil dibuat. Silakan catat peserta.');
    }

    public function destroyKegiatan(KegiatanPosyandu $kegiatan): RedirectResponse
    {
        $kegiatan->delete();
        return back()->with('success', 'Catatan kegiatan berhasil dihapus.');
    }

    public function showKegiatan(Posyandu $posyandu, KegiatanPosyandu $kegiatan)
    {
        abort_if($kegiatan->posyandu_id !== $posyandu->id, 404);

        $rekams  = $kegiatan->rekams()->orderBy('kategori')->orderBy('nama')->get();
        $balitas = Balita::where('posyandu_id', $posyandu->id)
                    ->orWhereNull('posyandu_id')
                    ->orderBy('nama')
                    ->get(['id', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'nama_ibu']);
        $bumils  = IbuHamil::where('posyandu_id', $posyandu->id)
                    ->orWhereNull('posyandu_id')
                    ->where('status', 'hamil')
                    ->orderBy('nama')
                    ->get(['id', 'nama', 'tanggal_lahir']);

        return view('admin.kesehatan.kegiatan-detail', compact('posyandu', 'kegiatan', 'rekams', 'balitas', 'bumils'));
    }

    public function storeRekam(Request $request, KegiatanPosyandu $kegiatan): RedirectResponse
    {
        $data = $request->validate([
            'kategori'       => 'required|in:balita,bumil,lansia,umum',
            'nama'           => 'required|string|max:150',
            'tanggal_lahir'  => 'nullable|date|before_or_equal:today',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'berat_badan'    => 'nullable|numeric|min:0.1|max:999',
            'tinggi_badan'   => 'nullable|numeric|min:1|max:300',
            'lingkar_kepala' => 'nullable|numeric|min:1|max:99',
            'tekanan_darah'  => 'nullable|string|max:20',
            'status_gizi'    => 'nullable|in:normal,kurang,buruk,lebih,stunting',
            'keterangan'     => 'nullable|string|max:500',
        ]);
        $data['kegiatan_posyandu_id'] = $kegiatan->id;

        RekamKegiatan::create($data);

        return back()->with('success', 'Data ' . $data['nama'] . ' berhasil dicatat.');
    }

    public function destroyRekam(RekamKegiatan $rekam): RedirectResponse
    {
        $rekam->delete();
        return back()->with('success', 'Data rekam berhasil dihapus.');
    }
}
