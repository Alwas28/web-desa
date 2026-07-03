<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Models\TimbangBalita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $posyandus = Posyandu::orderBy('nama')->get();
        $penduduk  = Penduduk::select('id', 'nik', 'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin')
                        ->orderBy('nama_lengkap')->get();

        $balitas = Balita::with(['posyandu', 'timbangTerakhir', 'timbang'])
            ->when($request->posyandu_id, fn ($q, $v) => $q->where('posyandu_id', $v))
            ->when($request->q, fn ($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('nama', 'like', "%{$v}%")
                  ->orWhere('nama_ibu', 'like', "%{$v}%")
                  ->orWhere('nik', 'like', "%{$v}%");
            }))
            ->orderBy('nama')
            ->get();

        $stats = [
            'total'    => $balitas->count(),
            'normal'   => $balitas->filter(fn ($b) => $b->timbangTerakhir?->status_gizi === 'normal')->count(),
            'stunting' => $balitas->filter(fn ($b) => $b->timbangTerakhir?->status_gizi === 'stunting')->count(),
            'masalah'  => $balitas->filter(fn ($b) => in_array($b->timbangTerakhir?->status_gizi, ['kurang', 'buruk', 'lebih']))->count(),
            'belum'    => $balitas->filter(fn ($b) => ! $b->timbangTerakhir)->count(),
        ];

        return view('admin.kesehatan.balita', compact('balitas', 'posyandus', 'stats', 'penduduk'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'posyandu_id'   => 'nullable|exists:posyandus,id',
            'nama'          => 'required|string|max:150',
            'nik'           => 'nullable|string|max:20',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ibu'      => 'required|string|max:150',
            'nama_ayah'     => 'nullable|string|max:150',
            'alamat'        => 'nullable|string|max:300',
        ]);

        Balita::create($data);

        return back()->with('success', 'Data balita "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Balita $balita): RedirectResponse
    {
        $data = $request->validate([
            'posyandu_id'   => 'nullable|exists:posyandus,id',
            'nama'          => 'required|string|max:150',
            'nik'           => 'nullable|string|max:20',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ibu'      => 'required|string|max:150',
            'nama_ayah'     => 'nullable|string|max:150',
            'alamat'        => 'nullable|string|max:300',
        ]);

        $balita->update($data);

        return back()->with('success', 'Data balita berhasil diperbarui.');
    }

    public function destroy(Balita $balita): RedirectResponse
    {
        $balita->delete();
        return back()->with('success', 'Data balita berhasil dihapus.');
    }

    public function storeRekam(Request $request, Balita $balita): RedirectResponse
    {
        $data = $request->validate([
            'tanggal'        => 'required|date|before_or_equal:today',
            'berat_badan'    => 'required|numeric|min:0.1|max:99',
            'tinggi_badan'   => 'nullable|numeric|min:1|max:200',
            'lingkar_kepala' => 'nullable|numeric|min:1|max:99',
            'status_gizi'    => 'required|in:normal,kurang,buruk,lebih,stunting',
            'keterangan'     => 'nullable|string|max:500',
        ]);
        $data['balita_id'] = $balita->id;

        TimbangBalita::create($data);

        return back()->with('success', 'Rekam timbang berhasil disimpan.');
    }

    public function destroyRekam(TimbangBalita $timbang): RedirectResponse
    {
        $timbang->delete();
        return back()->with('success', 'Rekam timbang berhasil dihapus.');
    }
}
