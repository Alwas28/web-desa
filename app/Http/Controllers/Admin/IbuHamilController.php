<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IbuHamil;
use App\Models\Penduduk;
use App\Models\KunjunganBumil;
use App\Models\Posyandu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IbuHamilController extends Controller
{
    public function index(Request $request)
    {
        $posyandus = Posyandu::orderBy('nama')->get();
        $penduduk  = Penduduk::select('id', 'nik', 'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin')
                        ->where('jenis_kelamin', 'P')
                        ->orderBy('nama_lengkap')->get();

        $ibuHamils = IbuHamil::with(['posyandu', 'kunjungan'])
            ->when($request->posyandu_id, fn ($q, $v) => $q->where('posyandu_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->q, fn ($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('nama', 'like', "%{$v}%")
                  ->orWhere('nik', 'like', "%{$v}%")
                  ->orWhere('nama_suami', 'like', "%{$v}%");
            }))
            ->orderByRaw("FIELD(status, 'hamil', 'selesai', 'keguguran')")
            ->orderBy('nama')
            ->get();

        $stats = [
            'hamil'         => $ibuHamils->where('status', 'hamil')->count(),
            'risiko_tinggi' => $ibuHamils->where('status', 'hamil')->where('status_risiko', 'risiko_tinggi')->count(),
            'risiko_rendah' => $ibuHamils->where('status', 'hamil')->where('status_risiko', 'risiko_rendah')->count(),
            'selesai'       => $ibuHamils->where('status', 'selesai')->count(),
        ];

        return view('admin.kesehatan.ibu-hamil', compact('ibuHamils', 'posyandus', 'stats', 'penduduk'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'posyandu_id'   => 'nullable|exists:posyandus,id',
            'nama'          => 'required|string|max:150',
            'nik'           => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date|before:today',
            'nama_suami'    => 'nullable|string|max:150',
            'alamat'        => 'nullable|string|max:300',
            'hpht'          => 'required|date|before_or_equal:today',
            'hpl'           => 'nullable|date|after:hpht',
            'status_risiko' => 'required|in:normal,risiko_rendah,risiko_tinggi',
            'status'        => 'required|in:hamil,selesai,keguguran',
            'keterangan'    => 'nullable|string|max:1000',
        ]);

        IbuHamil::create($data);

        return back()->with('success', 'Data ibu hamil "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, IbuHamil $ibuHamil): RedirectResponse
    {
        $data = $request->validate([
            'posyandu_id'   => 'nullable|exists:posyandus,id',
            'nama'          => 'required|string|max:150',
            'nik'           => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date|before:today',
            'nama_suami'    => 'nullable|string|max:150',
            'alamat'        => 'nullable|string|max:300',
            'hpht'          => 'required|date|before_or_equal:today',
            'hpl'           => 'nullable|date|after:hpht',
            'status_risiko' => 'required|in:normal,risiko_rendah,risiko_tinggi',
            'status'        => 'required|in:hamil,selesai,keguguran',
            'keterangan'    => 'nullable|string|max:1000',
        ]);

        $ibuHamil->update($data);

        return back()->with('success', 'Data ibu hamil berhasil diperbarui.');
    }

    public function destroy(IbuHamil $ibuHamil): RedirectResponse
    {
        $ibuHamil->delete();
        return back()->with('success', 'Data ibu hamil berhasil dihapus.');
    }

    public function storeKunjungan(Request $request, IbuHamil $ibuHamil): RedirectResponse
    {
        $data = $request->validate([
            'tanggal'         => 'required|date|before_or_equal:today',
            'tekanan_darah'   => 'nullable|string|max:20',
            'berat_badan'     => 'nullable|numeric|min:20|max:200',
            'usia_kehamilan'  => 'nullable|integer|min:1|max:45',
            'kunjungan_ke'    => 'nullable|integer|min:1|max:20',
            'keterangan'      => 'nullable|string|max:500',
        ]);
        $data['ibu_hamil_id'] = $ibuHamil->id;

        KunjunganBumil::create($data);

        return back()->with('success', 'Data kunjungan ANC berhasil disimpan.');
    }

    public function destroyKunjungan(KunjunganBumil $kunjungan): RedirectResponse
    {
        $kunjungan->delete();
        return back()->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
