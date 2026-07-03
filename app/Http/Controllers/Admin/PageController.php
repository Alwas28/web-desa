<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        return view('admin.page.index', [
            'pages'   => $query->paginate(15)->withQueryString(),
            'total'   => Page::count(),
            'draft'   => Page::where('status', 'draft')->count(),
            'publish' => Page::where('status', 'diterbitkan')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.page.form', ['page' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:pages,slug',
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
            $data['gambar'] = $request->file('gambar')->store('pages', 'public');
        }

        if ($data['status'] === 'diterbitkan' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Page::create($data);

        return redirect()->route('admin.page.index')
            ->with('success', 'Halaman "' . $data['judul'] . '" berhasil disimpan.');
    }

    public function edit(Page $page)
    {
        return view('admin.page.form', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'slug'           => ['nullable', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page)],
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
            if ($page->gambar) Storage::delete($page->gambar);
            $data['gambar'] = $request->file('gambar')->store('pages', 'public');
        } elseif ($request->input('hapus_gambar') === '1' && $page->gambar) {
            Storage::delete($page->gambar);
            $data['gambar'] = null;
        } else {
            unset($data['gambar']);
        }

        unset($data['hapus_gambar']);

        if ($data['status'] === 'diterbitkan' && ! $page->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $page->update($data);

        return redirect()->route('admin.page.index')
            ->with('success', 'Halaman "' . $page->judul . '" berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $judul = $page->judul;
        $page->delete();
        return back()->with('success', '"' . $judul . '" berhasil dihapus.');
    }
}
