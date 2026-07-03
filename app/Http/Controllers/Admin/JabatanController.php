<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        return view('admin.master.jabatan', [
            'jabatans' => Jabatan::orderBy('urutan')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'singkatan'  => 'nullable|string|max:20',
            'deskripsi'  => 'nullable|string|max:500',
            'urutan'     => 'nullable|integer|min:0|max:9999',
        ]);

        $data['urutan'] = $data['urutan'] ?? 0;

        Jabatan::create($data);

        return back()->with('success', 'Jabatan "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Jabatan $jabatan): RedirectResponse
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'singkatan'  => 'nullable|string|max:20',
            'deskripsi'  => 'nullable|string|max:500',
            'urutan'     => 'nullable|integer|min:0|max:9999',
        ]);

        $data['urutan'] = $data['urutan'] ?? 0;

        $jabatan->update($data);

        return back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        $jabatan->delete();
        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
