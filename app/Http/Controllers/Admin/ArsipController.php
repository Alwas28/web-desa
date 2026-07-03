<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\KategoriArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $query = Arsip::with('user')->latest('published_at');

        if ($q = $request->input('q')) {
            $query->where('judul', 'like', "%{$q}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($kat = $request->input('kategori')) {
            $query->where('kategori_arsip_id', $kat);
        }

        return view('admin.arsip.index', [
            'arsips'    => $query->with('kategoriArsip')->paginate(20)->withQueryString(),
            'total'     => Arsip::count(),
            'publish'   => Arsip::where('status', 'publish')->count(),
            'draft'     => Arsip::where('status', 'draft')->count(),
            'kategoris' => KategoriArsip::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'            => 'required|string|max:255',
            'kategori_arsip_id'=> 'nullable|exists:kategori_arsips,id',
            'deskripsi'        => 'nullable|string|max:1000',
            'file'             => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:10240',
            'status'           => 'required|in:draft,publish',
        ]);

        $file = $request->file('file');
        $path = $file->store('arsip', 'public');

        Arsip::create([
            'user_id'           => auth()->id(),
            'kategori_arsip_id' => $data['kategori_arsip_id'] ?? null,
            'judul'             => $data['judul'],
            'deskripsi'         => $data['deskripsi'] ?? null,
            'file'              => $path,
            'nama_file'         => $file->getClientOriginalName(),
            'mime_type'         => $file->getMimeType(),
            'ukuran'            => $file->getSize(),
            'status'            => $data['status'],
            'published_at'      => $data['status'] === 'publish' ? now() : null,
        ]);

        return back()->with('success', 'Arsip berhasil ditambahkan.');
    }

    public function update(Request $request, Arsip $arsip)
    {
        $data = $request->validate([
            'judul'            => 'required|string|max:255',
            'kategori_arsip_id'=> 'nullable|exists:kategori_arsips,id',
            'deskripsi'        => 'nullable|string|max:1000',
            'file'             => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:10240',
            'status'           => 'required|in:draft,publish',
        ]);

        $payload = [
            'judul'             => $data['judul'],
            'kategori_arsip_id' => $data['kategori_arsip_id'] ?? null,
            'deskripsi'         => $data['deskripsi'] ?? null,
            'status'            => $data['status'],
        ];

        if ($data['status'] === 'publish' && $arsip->status !== 'publish') {
            $payload['published_at'] = now();
        } elseif ($data['status'] === 'draft') {
            $payload['published_at'] = null;
        }

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($arsip->file)) {
                Storage::disk('public')->delete($arsip->file);
            }
            $file = $request->file('file');
            $payload['file']      = $file->store('arsip', 'public');
            $payload['nama_file'] = $file->getClientOriginalName();
            $payload['mime_type'] = $file->getMimeType();
            $payload['ukuran']    = $file->getSize();
            $payload['slug']      = null;
        }

        $arsip->update($payload);

        return back()->with('success', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        $arsip->delete();
        return back()->with('success', 'Arsip berhasil dihapus.');
    }
}
