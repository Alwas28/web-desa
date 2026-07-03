<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        return view('admin.pengumuman.index', [
            'pengumumans' => $query->paginate(15)->withQueryString(),
            'total'       => Pengumuman::count(),
            'terbit'      => Pengumuman::where('status', 'terbit')->count(),
            'draft'       => Pengumuman::where('status', 'draft')->count(),
            'nonaktif'    => Pengumuman::where('status', 'nonaktif')->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul'       => 'required|string|max:255',
            'konten'      => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'      => 'required|in:draft,terbit,nonaktif',
            'published_at'=> 'nullable|date',
        ]);

        $data['user_id'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        if ($data['status'] === 'terbit' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $data = $request->validate([
            'judul'        => 'required|string|max:255',
            'konten'       => 'required|string',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'hapus_gambar' => 'nullable',
            'status'       => 'required|in:draft,terbit',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) Storage::delete($pengumuman->gambar);
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        } elseif ($request->input('hapus_gambar') === '1' && $pengumuman->gambar) {
            Storage::delete($pengumuman->gambar);
            $data['gambar'] = null;
        } else {
            unset($data['gambar']);
        }

        unset($data['hapus_gambar']);

        if ($data['status'] === 'terbit' && ! $pengumuman->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $judul = $pengumuman->judul;
        $pengumuman->delete();
        return back()->with('success', '"' . $judul . '" berhasil dihapus.');
    }
}
