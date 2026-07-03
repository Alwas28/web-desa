<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class KartuKeluargaController extends Controller
{
    public function index(Request $request)
    {
        $q = KartuKeluarga::withCount('penduduks')
            ->with([
                'kepalaKeluarga:id,kk_id,nama_lengkap',
                'penduduks:id,kk_id,nama_lengkap,nik,hubungan_keluarga,jenis_kelamin,tanggal_lahir',
            ]);

        if ($search = $request->input('q')) {
            $q->where(function ($w) use ($search) {
                $w->where('nomor_kk', 'like', "%{$search}%")
                  ->orWhere('alamat',  'like', "%{$search}%")
                  ->orWhereHas('kepalaKeluarga', fn ($wh) => $wh->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        return view('admin.kk.index', [
            'kks'   => $q->latest()->paginate(20)->withQueryString(),
            'total' => KartuKeluarga::count(),
        ]);
    }

    public function show(KartuKeluarga $kartuKeluarga)
    {
        $kartuKeluarga->load([
            'penduduks' => fn ($q) => $q->orderByRaw(
                "FIELD(hubungan_keluarga,'kepala_keluarga','istri','anak','orang_tua','mertua','famili_lain','lainnya')"
            ),
        ]);
        return view('admin.kk.show', compact('kartuKeluarga'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_kk' => 'required|string|size:16|unique:kartu_keluargas,nomor_kk',
            'alamat'   => 'required|string',
            'rt'       => 'nullable|string|max:5',
            'rw'       => 'nullable|string|max:5',
            'dusun'    => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:5',
        ]);

        KartuKeluarga::create($data);

        return redirect()->route('admin.kk.index')->with('success', 'Kartu Keluarga berhasil ditambahkan.');
    }

    public function update(Request $request, KartuKeluarga $kartuKeluarga)
    {
        $data = $request->validate([
            'nomor_kk' => 'required|string|size:16|unique:kartu_keluargas,nomor_kk,' . $kartuKeluarga->id,
            'alamat'   => 'required|string',
            'rt'       => 'nullable|string|max:5',
            'rw'       => 'nullable|string|max:5',
            'dusun'    => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:5',
        ]);

        $kartuKeluarga->update($data);

        return redirect()->route('admin.kk.index')->with('success', 'Kartu Keluarga berhasil diperbarui.');
    }

    public function destroy(KartuKeluarga $kartuKeluarga)
    {
        $kartuKeluarga->delete();
        return redirect()->route('admin.kk.index')->with('success', 'Kartu Keluarga berhasil dihapus.');
    }
}
