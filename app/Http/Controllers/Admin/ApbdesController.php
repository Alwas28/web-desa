<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use App\Models\ApbdesPos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApbdesController extends Controller
{
    public function index()
    {
        $rows = Apbdes::with('pos')->orderByDesc('tahun')->get();

        return view('admin.apbdes.index', compact('rows'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tahun'   => 'required|integer|min:2000|max:2099|unique:apbdes,tahun',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $data['user_id'] = Auth::id();
        $data['status']  = 'draft';

        $apbdes = Apbdes::create($data);

        return redirect()->route('admin.apbdes.show', $apbdes)
            ->with('success', "APBDes tahun {$apbdes->tahun} berhasil dibuat.");
    }

    public function show(Apbdes $apbdes)
    {
        $apbdes->load(['pos' => fn($q) => $q->orderBy('urutan')->orderBy('id')]);

        $kelompokOptions = [
            'pendapatan' => ApbdesPos::kelompokOptions('pendapatan'),
            'belanja'    => ApbdesPos::kelompokOptions('belanja'),
            'pembiayaan' => ApbdesPos::kelompokOptions('pembiayaan'),
        ];

        return view('admin.apbdes.show', compact('apbdes', 'kelompokOptions'));
    }

    public function update(Request $request, Apbdes $apbdes): RedirectResponse
    {
        $data = $request->validate([
            'tahun'   => "required|integer|min:2000|max:2099|unique:apbdes,tahun,{$apbdes->id}",
            'status'  => 'required|in:draft,final',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $apbdes->update($data);

        return back()->with('success', "APBDes tahun {$apbdes->tahun} berhasil diperbarui.");
    }

    public function destroy(Apbdes $apbdes): RedirectResponse
    {
        $tahun = $apbdes->tahun;
        $apbdes->delete();

        return redirect()->route('admin.apbdes.index')
            ->with('success', "APBDes tahun {$tahun} berhasil dihapus.");
    }

    public function storePos(Request $request, Apbdes $apbdes): RedirectResponse
    {
        $data = $this->validatedPos($request);
        $data['apbdes_id'] = $apbdes->id;
        $data['urutan']    = ApbdesPos::where('apbdes_id', $apbdes->id)
                                      ->where('jenis', $data['jenis'])
                                      ->max('urutan') + 1;

        ApbdesPos::create($data);

        return back()->with('success', 'Pos anggaran berhasil ditambahkan.');
    }

    public function updatePos(Request $request, Apbdes $apbdes, ApbdesPos $pos): RedirectResponse
    {
        abort_if($pos->apbdes_id !== $apbdes->id, 404);

        $pos->update($this->validatedPos($request, $pos->id));

        return back()->with('success', 'Pos anggaran berhasil diperbarui.');
    }

    public function destroyPos(Apbdes $apbdes, ApbdesPos $pos): RedirectResponse
    {
        abort_if($pos->apbdes_id !== $apbdes->id, 404);

        $pos->delete();

        return back()->with('success', 'Pos anggaran berhasil dihapus.');
    }

    private function validatedPos(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'jenis'     => 'required|in:pendapatan,belanja,pembiayaan',
            'kelompok'  => 'required|string|max:150',
            'uraian'    => 'required|string|max:255',
            'anggaran'  => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
        ]);
    }
}
