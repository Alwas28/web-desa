<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisList = JenisSurat::orderBy('urutan')->orderBy('nama')->get();
        return view('admin.surat.jenis.index', compact('jenisList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:200|unique:jenis_surats,nama',
            'kode'         => 'nullable|string|max:20',
            'template_isi' => 'nullable|string|max:5000',
        ]);

        $validated['urutan'] = JenisSurat::max('urutan') + 1;
        JenisSurat::create($validated);

        return back()->with('success', 'Jenis surat "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function toggle(JenisSurat $jenisSurat)
    {
        $jenisSurat->update([
            'dapat_dibuat_masyarakat' => ! $jenisSurat->dapat_dibuat_masyarakat,
        ]);
        return response()->json(['aktif' => $jenisSurat->dapat_dibuat_masyarakat]);
    }

    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:200|unique:jenis_surats,nama,' . $jenisSurat->id,
            'kode'         => 'nullable|string|max:20',
            'template_isi' => 'nullable|string|max:5000',
        ]);

        $jenisSurat->update($validated);

        return back()->with('success', 'Jenis surat "' . $validated['nama'] . '" berhasil diperbarui.');
    }
}
