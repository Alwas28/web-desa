<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisBansos;
use Illuminate\Http\Request;

class JenisBansosController extends Controller
{
    public function index()
    {
        $jenis = JenisBansos::withCount('penerima')->orderBy('nama')->get();
        return view('admin.bansos.jenis', compact('jenis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:30|unique:jenis_bansos,kode',
            'nama'          => 'required|string|max:150',
            'deskripsi'     => 'nullable|string',
            'sumber_dana'   => 'required|in:APBN,APBD,APBDes,Swasta,Lainnya',
            'nilai_bantuan' => 'nullable|numeric|min:0',
            'satuan'        => 'nullable|string|max:60',
            'aktif'         => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        JenisBansos::create($data);

        return back()->with('success', 'Jenis bansos berhasil ditambahkan.');
    }

    public function update(Request $request, JenisBansos $jenisBanso)
    {
        $data = $request->validate([
            'kode'          => 'required|string|max:30|unique:jenis_bansos,kode,' . $jenisBanso->id,
            'nama'          => 'required|string|max:150',
            'deskripsi'     => 'nullable|string',
            'sumber_dana'   => 'required|in:APBN,APBD,APBDes,Swasta,Lainnya',
            'nilai_bantuan' => 'nullable|numeric|min:0',
            'satuan'        => 'nullable|string|max:60',
            'aktif'         => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        $jenisBanso->update($data);

        return back()->with('success', 'Jenis bansos berhasil diperbarui.');
    }

    public function destroy(JenisBansos $jenisBanso)
    {
        if ($jenisBanso->penerima()->exists()) {
            return back()->with('error', 'Tidak dapat dihapus karena sudah memiliki data penerima.');
        }
        $jenisBanso->delete();
        return back()->with('success', 'Jenis bansos berhasil dihapus.');
    }

    public function toggle(JenisBansos $jenisBanso)
    {
        $jenisBanso->update(['aktif' => !$jenisBanso->aktif]);
        return back()->with('success', 'Status jenis bansos diperbarui.');
    }
}
