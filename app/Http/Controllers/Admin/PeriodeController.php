<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        return view('admin.master.periode', [
            'periodes' => Periode::orderBy('mulai', 'desc')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'mulai'      => 'required|date',
            'selesai'    => 'nullable|date|after_or_equal:mulai',
            'keterangan' => 'nullable|string|max:500',
        ]);

        Periode::create($data);

        return back()->with('success', 'Periode "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    public function update(Request $request, Periode $periode): RedirectResponse
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'mulai'      => 'required|date',
            'selesai'    => 'nullable|date|after_or_equal:mulai',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $periode->update($data);

        return back()->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Periode $periode): RedirectResponse
    {
        $periode->delete();
        return back()->with('success', 'Periode berhasil dihapus.');
    }
}
