<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetaKategori;
use App\Models\PetaLokasi;
use App\Models\PetaWilayah;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $kategori = PetaKategori::withCount('lokasi')->orderBy('nama')->get();
        $stats = [
            'total'    => PetaLokasi::count(),
            'aktif'    => PetaLokasi::where('aktif', true)->count(),
            'kategori' => PetaKategori::count(),
            'wilayah'  => PetaWilayah::count(),
        ];
        return view('admin.peta.index', compact('kategori', 'stats'));
    }

    // ── Lokasi (JSON API) ─────────────────────────────────────────────────────────

    public function lokasiJson(Request $request)
    {
        $lokasi = PetaLokasi::with('kategori')
            ->when($request->filled('kategori'), fn($q) => $q->where('peta_kategori_id', $request->kategori))
            ->when($request->boolean('semua', false) === false, fn($q) => $q->where('aktif', true))
            ->get();

        return response()->json($lokasi->map(fn($l) => [
            'id'        => $l->id,
            'nama'      => $l->nama,
            'deskripsi' => $l->deskripsi,
            'alamat'    => $l->alamat,
            'lat'       => $l->latitude,
            'lng'       => $l->longitude,
            'aktif'     => $l->aktif,
            'kategori'  => $l->kategori ? [
                'id'    => $l->kategori->id,
                'nama'  => $l->kategori->nama,
                'ikon'  => $l->kategori->ikon,
                'warna' => $l->kategori->warna,
            ] : null,
        ]));
    }

    public function storeLokasi(Request $request)
    {
        $data = $request->validate([
            'peta_kategori_id' => 'nullable|exists:peta_kategori,id',
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'latitude'         => 'required|numeric|between:-90,90',
            'longitude'        => 'required|numeric|between:-180,180',
            'alamat'           => 'nullable|string',
            'aktif'            => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        $lokasi = PetaLokasi::create($data);
        $lokasi->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil ditambahkan.',
            'lokasi'  => $this->formatLokasi($lokasi),
        ]);
    }

    public function updateLokasi(Request $request, PetaLokasi $petaLokasi)
    {
        $data = $request->validate([
            'peta_kategori_id' => 'nullable|exists:peta_kategori,id',
            'nama'             => 'required|string|max:150',
            'deskripsi'        => 'nullable|string',
            'latitude'         => 'required|numeric|between:-90,90',
            'longitude'        => 'required|numeric|between:-180,180',
            'alamat'           => 'nullable|string',
            'aktif'            => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        $petaLokasi->update($data);
        $petaLokasi->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'lokasi'  => $this->formatLokasi($petaLokasi),
        ]);
    }

    public function destroyLokasi(PetaLokasi $petaLokasi)
    {
        $petaLokasi->delete();
        return response()->json(['success' => true, 'message' => 'Lokasi berhasil dihapus.']);
    }

    // ── Kategori (JSON API) ───────────────────────────────────────────────────────

    public function kategoriJson()
    {
        return response()->json(PetaKategori::withCount('lokasi')->orderBy('nama')->get());
    }

    public function storeKategori(Request $request)
    {
        $data = $request->validate([
            'nama'  => 'required|string|max:100',
            'ikon'  => 'required|string|max:60',
            'warna' => 'required|string|max:20',
        ]);

        $kat = PetaKategori::create($data);
        $kat->lokasi_count = 0;

        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.', 'kategori' => $kat]);
    }

    public function updateKategori(Request $request, PetaKategori $petaKategori)
    {
        $data = $request->validate([
            'nama'  => 'required|string|max:100',
            'ikon'  => 'required|string|max:60',
            'warna' => 'required|string|max:20',
        ]);

        $petaKategori->update($data);
        $petaKategori->lokasi_count = $petaKategori->lokasi()->count();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.', 'kategori' => $petaKategori]);
    }

    public function destroyKategori(PetaKategori $petaKategori)
    {
        if ($petaKategori->lokasi()->exists()) {
            return response()->json(['success' => false, 'message' => 'Kategori masih digunakan oleh ' . $petaKategori->lokasi()->count() . ' lokasi.'], 422);
        }
        $petaKategori->delete();
        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
    }

    // ── Wilayah (JSON API) ────────────────────────────────────────────────────────

    public function wilayahJson()
    {
        return response()->json(PetaWilayah::orderBy('nama')->get()->map(fn($w) => [
            'id'          => $w->id,
            'nama'        => $w->nama,
            'tipe'        => $w->tipe,
            'tipe_label'  => $w->tipe_label,
            'geojson'     => json_decode($w->geojson),
            'warna'       => $w->warna,
            'opacity'     => $w->opacity,
            'keterangan'  => $w->keterangan,
            'aktif'       => $w->aktif,
        ]));
    }

    public function storeWilayah(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'tipe'       => 'required|in:desa,dusun,rt_rw,sawah,hutan,lainnya',
            'geojson'    => 'required|json',
            'warna'      => 'required|string|max:20',
            'opacity'    => 'required|numeric|between:0,1',
            'keterangan' => 'nullable|string',
            'aktif'      => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        $w = PetaWilayah::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Batas wilayah berhasil disimpan.',
            'wilayah' => [
                'id' => $w->id, 'nama' => $w->nama, 'tipe' => $w->tipe,
                'tipe_label' => $w->tipe_label, 'geojson' => json_decode($w->geojson),
                'warna' => $w->warna, 'opacity' => $w->opacity,
                'keterangan' => $w->keterangan, 'aktif' => $w->aktif,
            ],
        ]);
    }

    public function updateWilayah(Request $request, PetaWilayah $petaWilayah)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:150',
            'tipe'       => 'required|in:desa,dusun,rt_rw,sawah,hutan,lainnya',
            'geojson'    => 'nullable|json',
            'warna'      => 'required|string|max:20',
            'opacity'    => 'required|numeric|between:0,1',
            'keterangan' => 'nullable|string',
            'aktif'      => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        if (empty($data['geojson'])) {
            unset($data['geojson']); // keep existing geojson if not provided
        }
        $petaWilayah->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Batas wilayah berhasil diperbarui.',
            'wilayah' => [
                'id' => $petaWilayah->id, 'nama' => $petaWilayah->nama,
                'tipe' => $petaWilayah->tipe, 'tipe_label' => $petaWilayah->tipe_label,
                'geojson' => json_decode($petaWilayah->geojson),
                'warna' => $petaWilayah->warna, 'opacity' => $petaWilayah->opacity,
                'keterangan' => $petaWilayah->keterangan, 'aktif' => $petaWilayah->aktif,
            ],
        ]);
    }

    public function destroyWilayah(PetaWilayah $petaWilayah)
    {
        $petaWilayah->delete();
        return response()->json(['success' => true, 'message' => 'Batas wilayah berhasil dihapus.']);
    }

    private function formatLokasi(PetaLokasi $l): array
    {
        return [
            'id'        => $l->id,
            'nama'      => $l->nama,
            'deskripsi' => $l->deskripsi,
            'alamat'    => $l->alamat,
            'lat'       => $l->latitude,
            'lng'       => $l->longitude,
            'aktif'     => $l->aktif,
            'peta_kategori_id' => $l->peta_kategori_id,
            'kategori'  => $l->kategori ? [
                'id'    => $l->kategori->id,
                'nama'  => $l->kategori->nama,
                'ikon'  => $l->kategori->ikon,
                'warna' => $l->kategori->warna,
            ] : null,
        ];
    }
}
