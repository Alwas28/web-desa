<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.master.kategori', [
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'slug'      => ['nullable','string','max:100','unique:kategoris,slug','regex:/^[a-z0-9-]+$/'],
            'warna'     => 'nullable|string|max:7',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nama']);
        }

        Kategori::create($data);

        return back()->with('success', 'Kategori "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'slug'      => ['nullable','string','max:100',Rule::unique('kategoris','slug')->ignore($kategori),'regex:/^[a-z0-9-]+$/'],
            'warna'     => 'nullable|string|max:7',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nama']);
        }

        $kategori->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
