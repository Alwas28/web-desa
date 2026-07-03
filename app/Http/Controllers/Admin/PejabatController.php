<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Pejabat;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PejabatController extends Controller
{
    public function index()
    {
        return view('admin.master.pejabat', [
            'pejabats' => Pejabat::with(['jabatan', 'periode'])
                ->orderBy('jabatan_id')
                ->orderByDesc('periode_id')
                ->get(),
            'jabatans' => Jabatan::orderBy('urutan')->orderBy('nama')->get(),
            'periodes' => Periode::orderByDesc('mulai')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'jabatan_id'    => 'required|exists:jabatans,id',
            'periode_id'    => 'required|exists:periodes,id',
            'nama'          => 'required|string|max:150',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nip'           => 'nullable|string|max:30',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan'    => 'nullable|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pejabat', 'public');
        }

        Pejabat::create($data);

        return back()->with('success', $data['nama'] . ' berhasil ditambahkan.');
    }

    public function update(Request $request, Pejabat $pejabat): RedirectResponse
    {
        $data = $request->validate([
            'jabatan_id'    => 'required|exists:jabatans,id',
            'periode_id'    => 'required|exists:periodes,id',
            'nama'          => 'required|string|max:150',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_foto'    => 'nullable|boolean',
            'nip'           => 'nullable|string|max:30',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan'    => 'nullable|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('foto')) {
            if ($pejabat->foto) Storage::delete($pejabat->foto);
            $data['foto'] = $request->file('foto')->store('pejabat', 'public');
        } elseif ($request->boolean('hapus_foto') && $pejabat->foto) {
            Storage::delete($pejabat->foto);
            $data['foto'] = null;
        } else {
            unset($data['foto']);
        }

        unset($data['hapus_foto']);
        $pejabat->update($data);

        return back()->with('success', $pejabat->nama . ' berhasil diperbarui.');
    }

    public function destroy(Pejabat $pejabat): RedirectResponse
    {
        $nama = $pejabat->nama;
        $pejabat->delete(); // foto dihapus via model booted()
        return back()->with('success', $nama . ' berhasil dihapus.');
    }
}
