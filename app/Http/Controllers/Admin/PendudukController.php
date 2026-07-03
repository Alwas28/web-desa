<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $totalPenduduk = Penduduk::count();
        $laki          = Penduduk::where('jenis_kelamin', 'L')->count();
        $perempuan     = Penduduk::where('jenis_kelamin', 'P')->count();
        $totalKK       = KartuKeluarga::count();

        $q = Penduduk::with('kartuKeluarga')->latest();

        if ($s = $request->input('q')) {
            $q->where(fn ($w) => $w->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
        }
        if ($jk = $request->input('jk')) $q->where('jenis_kelamin', $jk);
        if ($sp = $request->input('sp')) $q->where('status_perkawinan', $sp);

        $penduduks = $q->paginate(25)->withQueryString();
        $kkList    = KartuKeluarga::orderBy('nomor_kk')->get(['id', 'nomor_kk', 'alamat']);

        return view('admin.penduduk.index', compact(
            'totalPenduduk', 'laki', 'perempuan', 'totalKK',
            'penduduks', 'kkList'
        ));
    }

    public function cari(Request $request)
    {
        $q = $request->input('q', '');
        $results = Penduduk::with('kartuKeluarga:id,alamat')
            ->where(fn ($w) => $w->where('nama_lengkap', 'like', "%{$q}%")->orWhere('nik', 'like', "%{$q}%"))
            ->limit(15)
            ->get(['id', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'status_perkawinan', 'pekerjaan', 'kk_id'])
            ->map(fn ($p) => [
                'id'           => $p->id,
                'nik'          => $p->nik,
                'nama'         => $p->nama_lengkap,
                'ttl'          => $p->tempat_lahir . ', ' . $p->tanggal_lahir->format('d/m/Y'),
                'jk'           => $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'agama'        => $p->agama,
                'status_kawin' => $p->status_perkawinan_label,
                'pekerjaan'    => $p->pekerjaan ?: '-',
                'alamat'       => $p->kartuKeluarga?->alamat ?? '-',
            ]);

        return response()->json($results);
    }

    public function show(Penduduk $penduduk)
    {
        $penduduk->load('kartuKeluarga');
        return view('admin.penduduk.show', compact('penduduk'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kk_id'             => 'nullable|exists:kartu_keluargas,id',
            'nik'               => 'required|string|size:16|unique:penduduks,nik',
            'nama_lengkap'      => 'required|string|max:150',
            'tempat_lahir'      => 'required|string|max:100',
            'tanggal_lahir'     => 'required|date|before:today',
            'jenis_kelamin'     => 'required|in:L,P',
            'agama'             => 'required|string|max:50',
            'status_perkawinan' => 'required|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'pekerjaan'         => 'nullable|string|max:100',
            'pendidikan'        => 'required|in:tidak_sekolah,sd,smp,sma,diploma,s1,s2,s3',
            'golongan_darah'    => 'nullable|in:A,B,AB,O',
            'hubungan_keluarga' => 'required|in:kepala_keluarga,istri,anak,orang_tua,mertua,famili_lain,lainnya',
            'status_penduduk'   => 'required|in:tetap,sementara',
            'kewarganegaraan'   => 'required|string|max:10',
        ]);

        // Pastikan 1 KK hanya punya 1 kepala_keluarga dan 1 istri
        if (! empty($data['kk_id']) && in_array($data['hubungan_keluarga'], ['kepala_keluarga', 'istri'])) {
            $label  = $data['hubungan_keluarga'] === 'kepala_keluarga' ? 'Kepala Keluarga' : 'Istri';
            $exists = Penduduk::where('kk_id', $data['kk_id'])
                ->where('hubungan_keluarga', $data['hubungan_keluarga'])
                ->exists();
            if ($exists) {
                return back()->withInput()
                    ->withErrors(['hubungan_keluarga' => "KK ini sudah memiliki {$label}. Setiap KK hanya boleh 1 {$label}."]);
            }
        }

        Penduduk::create($data);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $data = $request->validate([
            'kk_id'             => 'nullable|exists:kartu_keluargas,id',
            'nik'               => 'required|string|size:16|unique:penduduks,nik,' . $penduduk->id,
            'nama_lengkap'      => 'required|string|max:150',
            'tempat_lahir'      => 'required|string|max:100',
            'tanggal_lahir'     => 'required|date|before:today',
            'jenis_kelamin'     => 'required|in:L,P',
            'agama'             => 'required|string|max:50',
            'status_perkawinan' => 'required|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'pekerjaan'         => 'nullable|string|max:100',
            'pendidikan'        => 'required|in:tidak_sekolah,sd,smp,sma,diploma,s1,s2,s3',
            'golongan_darah'    => 'nullable|in:A,B,AB,O',
            'hubungan_keluarga' => 'required|in:kepala_keluarga,istri,anak,orang_tua,mertua,famili_lain,lainnya',
            'status_penduduk'   => 'required|in:tetap,sementara',
            'kewarganegaraan'   => 'required|string|max:10',
        ]);

        // Pastikan 1 KK hanya punya 1 kepala_keluarga dan 1 istri (kecuali penduduk ini sendiri)
        if (! empty($data['kk_id']) && in_array($data['hubungan_keluarga'], ['kepala_keluarga', 'istri'])) {
            $label  = $data['hubungan_keluarga'] === 'kepala_keluarga' ? 'Kepala Keluarga' : 'Istri';
            $exists = Penduduk::where('kk_id', $data['kk_id'])
                ->where('hubungan_keluarga', $data['hubungan_keluarga'])
                ->where('id', '!=', $penduduk->id)
                ->exists();
            if ($exists) {
                return back()->withInput()
                    ->withErrors(['hubungan_keluarga' => "KK ini sudah memiliki {$label}. Setiap KK hanya boleh 1 {$label}."]);
            }
        }

        $penduduk->update($data);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil dihapus.');
    }
}
