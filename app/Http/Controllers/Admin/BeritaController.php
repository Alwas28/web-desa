<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::with(['user', 'kategori'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        return view('admin.berita.index', [
            'beritas'   => $query->paginate(15)->withQueryString(),
            'kategoris' => Kategori::orderBy('nama')->get(),
            'total'     => Berita::count(),
            'draft'     => Berita::where('status', 'draft')->count(),
            'publish'   => Berita::where('status', 'diterbitkan')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.berita.form', [
            'berita'    => null,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:beritas,slug',
            'kategori_id'    => 'nullable|exists:kategoris,id',
            'konten'         => 'required|string',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'         => 'required|in:draft,diterbitkan',
            'published_at'   => 'nullable|date',
            'meta_title'     => 'nullable|string|max:255',
            'meta_deskripsi' => 'nullable|string|max:500',
            'meta_keywords'  => 'nullable|string|max:500',
        ]);

        $data['user_id'] = auth()->id();
        $data['slug']    = $data['slug'] ?: Str::slug($data['judul']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        if ($data['status'] === 'diterbitkan' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')
            ->with('success', '"' . $data['judul'] . '" berhasil dipublikasikan.');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.form', [
            'berita'    => $berita,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'slug'           => ['nullable','string','max:255', Rule::unique('beritas','slug')->ignore($berita)],
            'kategori_id'    => 'nullable|exists:kategoris,id',
            'konten'         => 'required|string',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hapus_gambar'   => 'nullable',
            'status'         => 'required|in:draft,diterbitkan',
            'published_at'   => 'nullable|date',
            'meta_title'     => 'nullable|string|max:255',
            'meta_deskripsi' => 'nullable|string|max:500',
            'meta_keywords'  => 'nullable|string|max:500',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['judul']);

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) Storage::delete($berita->gambar);
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        } elseif ($request->input('hapus_gambar') === '1' && $berita->gambar) {
            Storage::delete($berita->gambar);
            $data['gambar'] = null;
        } else {
            unset($data['gambar']);
        }

        unset($data['hapus_gambar']);

        if ($data['status'] === 'diterbitkan' && ! $berita->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', '"' . $berita->judul . '" berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        $judul = $berita->judul;
        $berita->delete();
        return back()->with('success', '"' . $judul . '" berhasil dihapus.');
    }
}
