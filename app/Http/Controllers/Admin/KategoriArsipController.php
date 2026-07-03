<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriArsip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriArsipController extends Controller
{
    public function index()
    {
        return view('admin.master.kategori_arsip', [
            'kategoris' => KategoriArsip::withCount('arsips')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'slug'      => ['nullable', 'string', 'max:100', 'unique:kategori_arsips,slug', 'regex:/^[a-z0-9-]+$/'],
            'warna'     => 'nullable|string|max:7',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nama']);
        }

        KategoriArsip::create($data);

        return back()->with('success', 'Kategori "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriArsip $kategoriArsip): RedirectResponse
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'slug'      => ['nullable', 'string', 'max:100', Rule::unique('kategori_arsips', 'slug')->ignore($kategoriArsip), 'regex:/^[a-z0-9-]+$/'],
            'warna'     => 'nullable|string|max:7',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nama']);
        }

        $kategoriArsip->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriArsip $kategoriArsip): RedirectResponse
    {
        $kategoriArsip->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
