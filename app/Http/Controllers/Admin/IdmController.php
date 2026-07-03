<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdmData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdmController extends Controller
{
    public function index()
    {
        $rows          = IdmData::orderByDesc('tahun')->get();
        $latest        = $rows->first();
        $indikatorDefs = IdmData::indikatorDefs();

        return view('admin.idm.index', compact('rows', 'latest', 'indikatorDefs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id']    = Auth::id();
        $data['skor_idm']   = round(($data['skor_iks'] + $data['skor_ike'] + $data['skor_ikl']) / 3, 4);
        $data['status_idm'] = IdmData::statusFromSkor($data['skor_idm']);

        IdmData::create($data);

        return back()->with('success', "Data IDM tahun {$data['tahun']} berhasil disimpan.");
    }

    public function update(Request $request, IdmData $idm): RedirectResponse
    {
        $data = $this->validated($request, $idm->id);
        $data['skor_idm']   = round(($data['skor_iks'] + $data['skor_ike'] + $data['skor_ikl']) / 3, 4);
        $data['status_idm'] = IdmData::statusFromSkor($data['skor_idm']);

        $idm->update($data);

        return back()->with('success', "Data IDM tahun {$idm->tahun} berhasil diperbarui.");
    }

    public function destroy(IdmData $idm): RedirectResponse
    {
        $tahun = $idm->tahun;
        $idm->delete();

        return back()->with('success', "Data IDM tahun {$tahun} berhasil dihapus.");
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.mimes' => 'File harus berformat CSV dari idm.kemendesa.go.id.',
        ]);

        $path  = $request->file('file')->getRealPath();
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$lines) {
            return back()->with('error', 'File CSV kosong atau tidak dapat dibaca.');
        }

        // Hapus UTF-8 BOM dari baris pertama
        $lines[0] = preg_replace('/^\xEF\xBB\xBF/', '', $lines[0]);

        $data           = [];
        $tahun          = null;
        $currentSection = 'iks'; // state machine: iks → ike → ikl → done
        $indikators     = ['iks' => [], 'ike' => [], 'ikl' => []];

        foreach ($lines as $line) {
            $row = str_getcsv($line);
            $row = array_map(fn($v) => trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $v ?? '')), $row);

            if (count($row) < 3) continue;

            $no    = $row[0];
            $label = $row[1];
            $value = $row[2];

            // ── Baris ringkasan (NO kosong, label "IKS 2024" dst.) ──
            if (preg_match('/^IKS\s+(\d{4})$/i', $label, $m)) {
                $tahun              = (int) $m[1];
                $data['skor_iks']   = (float) str_replace(',', '.', $value);
                $currentSection     = 'ike';

            } elseif (preg_match('/^IKE\s+(\d{4})$/i', $label, $m)) {
                $data['skor_ike']   = (float) str_replace(',', '.', $value);
                $currentSection     = 'ikl';

            } elseif (preg_match('/^IKL\s+(\d{4})$/i', $label, $m)) {
                $data['skor_ikl']   = (float) str_replace(',', '.', $value);
                $currentSection     = 'done';

            } elseif (preg_match('/^IDM\s+(\d{4})$/i', $label, $m)) {
                $data['skor_idm']   = (float) str_replace(',', '.', $value);

            } elseif (preg_match('/^STATUS\s+IDM\s+(\d{4})$/i', $label, $m)) {
                $data['status_idm'] = $this->normalizeStatus($value);

            // ── Baris indikator (NO berupa angka, label ada, nilai numerik) ──
            } elseif (
                is_numeric($no)
                && $label !== ''
                && $label !== 'INDIKATOR IDM'
                && isset($indikators[$currentSection])
                && is_numeric($value)
            ) {
                $key = $this->labelToKey($label);
                $indikators[$currentSection][$key] = (float) $value;
            }
        }

        if (!$tahun || !isset($data['skor_idm'], $data['skor_iks'], $data['skor_ike'], $data['skor_ikl'])) {
            return back()->with('error',
                'Format CSV tidak dikenali. Pastikan file diunduh langsung dari idm.kemendesa.go.id.'
            );
        }

        $data['status_idm'] ??= IdmData::statusFromSkor($data['skor_idm']);
        $data['tahun']        = $tahun;
        $data['user_id']      = Auth::id();
        $data['indikators']   = $indikators;

        $action = IdmData::where('tahun', $tahun)->exists() ? 'diperbarui' : 'diimpor';

        IdmData::updateOrCreate(['tahun' => $tahun], $data);

        $total = count($indikators['iks']) + count($indikators['ike']) + count($indikators['ikl']);

        return back()->with('success',
            "Data IDM tahun {$tahun} berhasil {$action} — {$total} indikator tersimpan."
        );
    }

    private function labelToKey(string $label): string
    {
        $key = preg_replace('/^skor\s+/i', '', trim($label));
        $key = strtolower($key);
        $key = preg_replace('/[\/\&]+/', '_', $key);
        $key = preg_replace('/\s+/', '_', $key);
        $key = preg_replace('/[^a-z0-9_]/', '', $key);
        $key = preg_replace('/_+/', '_', $key);
        return trim($key, '_');
    }

    private function normalizeStatus(string $raw): string
    {
        $map = [
            'sangat tertinggal' => 'Sangat Tertinggal',
            'tertinggal'        => 'Tertinggal',
            'berkembang'        => 'Berkembang',
            'maju'              => 'Maju',
            'mandiri'           => 'Mandiri',
        ];

        return $map[strtolower(trim($raw))] ?? 'Berkembang';
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'tahun'    => "required|integer|min:2000|max:2099|unique:idm_data,tahun" . ($ignoreId ? ",{$ignoreId}" : ''),
            'skor_iks' => 'required|numeric|min:0|max:1',
            'skor_ike' => 'required|numeric|min:0|max:1',
            'skor_ikl' => 'required|numeric|min:0|max:1',
            'catatan'  => 'nullable|string|max:1000',
        ]);
    }
}
