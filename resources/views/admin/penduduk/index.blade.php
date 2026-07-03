@extends('layouts.admin')

@section('title', 'Data Penduduk')
@section('page-title', 'Data Penduduk')
@section('page-sub', 'Kelola data kependudukan warga desa')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
  @foreach([
    ['label'=>'Total KK',       'val'=>$totalKK,       'icon'=>'ti-home',         'color'=>'brand'],
    ['label'=>'Total Penduduk', 'val'=>$totalPenduduk, 'icon'=>'ti-users',         'color'=>'brand'],
    ['label'=>'Laki-laki',      'val'=>$laki,          'icon'=>'ti-gender-male',   'color'=>'blue'],
    ['label'=>'Perempuan',      'val'=>$perempuan,     'icon'=>'ti-gender-female', 'color'=>'pink'],
  ] as $s)
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10 grid place-items-center flex-shrink-0">
      <i class="ti {{ $s['icon'] }} text-lg text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
    </div>
    <div>
      <div class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($s['val']) }}</div>
      <div class="text-xs text-slate-400">{{ $s['label'] }}</div>
    </div>
  </div>
  @endforeach
</div>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

{{-- Filter --}}
<form method="GET" action="{{ route('admin.penduduk.index') }}" class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / NIK…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>
  <select name="jk" class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua JK</option>
    <option value="L" {{ request('jk')==='L' ? 'selected':'' }}>Laki-laki</option>
    <option value="P" {{ request('jk')==='P' ? 'selected':'' }}>Perempuan</option>
  </select>
  <select name="sp" class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none">
    <option value="">Semua Status Kawin</option>
    <option value="belum_kawin" {{ request('sp')==='belum_kawin' ? 'selected':'' }}>Belum Kawin</option>
    <option value="kawin"       {{ request('sp')==='kawin'       ? 'selected':'' }}>Kawin</option>
    <option value="cerai_hidup" {{ request('sp')==='cerai_hidup' ? 'selected':'' }}>Cerai Hidup</option>
    <option value="cerai_mati"  {{ request('sp')==='cerai_mati'  ? 'selected':'' }}>Cerai Mati</option>
  </select>
  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i>
  </button>
  @if(request()->hasAny(['q','jk','sp']))
  <a href="{{ route('admin.penduduk.index') }}"
     class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
    <i class="ti ti-x text-sm"></i>
  </a>
  @endif
  <button type="button" onclick="openPendudukModal()"
    class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus text-base"></i> Tambah Penduduk
  </button>
</form>

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($penduduks->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-user-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400">
        {{ request()->hasAny(['q','jk','sp']) ? 'Tidak ada data yang sesuai filter.' : 'Belum ada data penduduk.' }}
      </p>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 uppercase tracking-wide text-left">
            <th class="px-4 py-3">Nama / NIK</th>
            <th class="px-4 py-3">Tgl Lahir / Umur</th>
            <th class="px-4 py-3">JK</th>
            <th class="px-4 py-3">Status Kawin</th>
            <th class="px-4 py-3">Hubungan KK</th>
            <th class="px-4 py-3">No. KK</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($penduduks as $p)
          <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $p->nama_lengkap }}</div>
              <div class="text-xs font-mono text-slate-400">{{ $p->nik }}</div>
            </td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
              <div>{{ $p->tanggal_lahir->format('d M Y') }}</div>
              <div class="font-semibold text-slate-700 dark:text-slate-300">{{ $p->umur }} thn
                <span class="text-slate-400 font-normal">({{ $p->kelompok_umur }})</span>
              </div>
            </td>
            <td class="px-4 py-3">
              @if($p->jenis_kelamin === 'L')
                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                  <i class="ti ti-gender-male text-[11px]"></i> L
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-pink-100 text-pink-700 dark:bg-pink-500/20 dark:text-pink-400">
                  <i class="ti ti-gender-female text-[11px]"></i> P
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $p->status_perkawinan_label }}</td>
            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $p->hubungan_label }}</td>
            <td class="px-4 py-3 text-xs font-mono text-slate-500 dark:text-slate-400">
              {{ $p->kartuKeluarga?->nomor_kk ?? '—' }}
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('admin.penduduk.show', $p) }}"
                  class="flex items-center gap-1 px-3 h-8 rounded-lg border border-brand-200 dark:border-brand-500/30 text-brand-600 dark:text-brand-400 text-xs hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
                  <i class="ti ti-user text-sm"></i> Profil
                </a>
                <button type="button" onclick="openPendudukModal(@js($p->toArray()))"
                  class="flex items-center gap-1 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                  <i class="ti ti-pencil text-sm"></i>
                </button>
                <form method="POST" action="{{ route('admin.penduduk.destroy', $p) }}" class="m-0">
                  @csrf @method('DELETE')
                  <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Data penduduk {{ addslashes($p->nama_lengkap) }} akan dihapus permanen.', 'Hapus Data Penduduk')"
                    class="flex items-center px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                    <i class="ti ti-trash text-sm"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($penduduks->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $penduduks->links() }}
      </div>
    @endif
  @endif
</div>

{{-- ══ MODAL PENDUDUK ══════════════════════════════════════════════════════ --}}
<div id="modalPenduduk" style="display:none"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closePendudukModal()">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h2 class="font-semibold text-base" id="mpTitle">Tambah Penduduk</h2>
        <button type="button" onclick="closePendudukModal()"
          class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <form id="fmPenduduk" method="POST" class="p-6">
        @csrf
        <div id="mpMethod"></div>
        <input type="hidden" name="_id" id="mp_id">

        @if($errors->any())
        <div class="mb-4 flex items-start gap-3 px-4 py-3 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 text-sm">
          <i class="ti ti-alert-circle flex-shrink-0 mt-0.5"></i>
          <div>
            <p class="font-semibold mb-0.5">Gagal menyimpan data</p>
            <ul class="list-disc list-inside space-y-0.5">
              @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        </div>
        @endif

        <div class="grid grid-cols-2 gap-4">

          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1.5">NIK <span class="text-rose-500">*</span></label>
            <input type="text" name="nik" id="mp_nik" required maxlength="16" minlength="16"
              inputmode="numeric" pattern="[0-9]{16}"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              placeholder="16 digit NIK">
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_lengkap" id="mp_nama" required maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Tempat Lahir <span class="text-rose-500">*</span></label>
            <input type="text" name="tempat_lahir" id="mp_tempat" required maxlength="100"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
            <input type="date" name="tanggal_lahir" id="mp_tgl" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
            <select name="jenis_kelamin" id="mp_jk" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">-- pilih --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Agama <span class="text-rose-500">*</span></label>
            <select name="agama" id="mp_agama" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">-- pilih --</option>
              @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $a)
                <option value="{{ $a }}">{{ $a }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Status Perkawinan <span class="text-rose-500">*</span></label>
            <select name="status_perkawinan" id="mp_sp" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">-- pilih --</option>
              <option value="belum_kawin">Belum Kawin</option>
              <option value="kawin">Kawin</option>
              <option value="cerai_hidup">Cerai Hidup</option>
              <option value="cerai_mati">Cerai Mati</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Pekerjaan</label>
            <select name="pekerjaan" id="mp_pekerjaan"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">-- pilih --</option>
              <option>Petani</option>
              <option>Nelayan</option>
              <option>Peternak</option>
              <option>Pedagang</option>
              <option>Wiraswasta</option>
              <option>Buruh Tani</option>
              <option>Buruh Harian</option>
              <option>PNS / ASN</option>
              <option>TNI</option>
              <option>POLRI</option>
              <option>Pegawai Swasta</option>
              <option>Guru / Pendidik</option>
              <option>Tenaga Kesehatan</option>
              <option>Pelajar / Mahasiswa</option>
              <option>Ibu Rumah Tangga</option>
              <option>Pensiunan</option>
              <option>Tidak Bekerja</option>
              <option>Lainnya</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
            <select name="pendidikan" id="mp_pendidikan" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">-- pilih --</option>
              <option value="tidak_sekolah">Tidak Sekolah</option>
              <option value="sd">SD/Sederajat</option>
              <option value="smp">SMP/Sederajat</option>
              <option value="sma">SMA/Sederajat</option>
              <option value="diploma">Diploma (D1–D3)</option>
              <option value="s1">Sarjana (S1)</option>
              <option value="s2">Magister (S2)</option>
              <option value="s3">Doktor (S3)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Golongan Darah</label>
            <select name="golongan_darah" id="mp_gd"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">Tidak Tahu</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="AB">AB</option>
              <option value="O">O</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Hubungan dalam KK <span class="text-rose-500">*</span></label>
            <select name="hubungan_keluarga" id="mp_hub" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="kepala_keluarga">Kepala Keluarga</option>
              <option value="istri">Istri</option>
              <option value="anak">Anak</option>
              <option value="orang_tua">Orang Tua</option>
              <option value="mertua">Mertua</option>
              <option value="famili_lain">Famili Lain</option>
              <option value="lainnya">Lainnya</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Status Penduduk <span class="text-rose-500">*</span></label>
            <select name="status_penduduk" id="mp_statuspend"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="tetap">Tetap</option>
              <option value="sementara">Sementara</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Kewarganegaraan <span class="text-rose-500">*</span></label>
            <input type="text" name="kewarganegaraan" id="mp_wni" maxlength="10"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Kartu Keluarga</label>
            <input type="hidden" name="kk_id" id="mp_kk">
            <div class="relative">
              <div class="flex items-center h-9 px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus-within:ring-2 focus-within:ring-brand-500 focus-within:border-transparent">
                <i class="ti ti-home text-slate-400 text-sm flex-shrink-0 mr-2"></i>
                <input type="text" id="mp_kk_text" placeholder="Cari nomor KK…" autocomplete="off"
                  class="flex-1 bg-transparent text-sm outline-none border-0 p-0 min-w-0"
                  oninput="kkSearch(this.value)" onfocus="kkOpen()">
                <button type="button" id="mp_kk_clear" onclick="kkClear()" style="display:none"
                  class="ml-1 text-slate-300 hover:text-slate-500 flex-shrink-0">
                  <i class="ti ti-x text-xs"></i>
                </button>
              </div>
              <div id="mp_kk_drop" style="display:none"
                class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-xl z-[60] max-h-52 overflow-y-auto">
              </div>
            </div>
          </div>

        </div>{{-- /grid --}}

        <div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex gap-3">
          <button type="button" onclick="closePendudukModal()"
            class="flex-1 h-10 rounded-lg border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 h-10 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-device-floppy text-base"></i> Simpan
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const STORE_URL = '{{ route('admin.penduduk.store') }}';
const BASE_URL  = '{{ url('admin/penduduk') }}/';
const KK_DATA   = @js($kkList);

/* ── Searchable KK Combobox ─────────────────────────────────────────────── */
function kkRender(search) {
  const drop = document.getElementById('mp_kk_drop');
  const q    = (search || '').toLowerCase();
  const hits = KK_DATA.filter(k =>
    k.nomor_kk.includes(q) ||
    (k.alamat && k.alamat.toLowerCase().includes(q))
  ).slice(0, 60);

  if (!hits.length) {
    drop.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400">Tidak ditemukan</div>';
    return;
  }
  drop.innerHTML = hits.map(k => `
    <button type="button"
      class="w-full text-left px-4 py-2.5 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
      onmousedown="kkSelect(${k.id},'${k.nomor_kk}')">
      <div class="font-mono font-semibold text-slate-900 dark:text-slate-100 text-xs">${k.nomor_kk}</div>
      <div class="text-xs text-slate-400 truncate">${k.alamat ?? ''}</div>
    </button>
  `).join('');
}

function kkOpen() {
  kkRender(document.getElementById('mp_kk_text').value);
  document.getElementById('mp_kk_drop').style.display = 'block';
}

function kkSearch(val) {
  document.getElementById('mp_kk').value = '';
  kkRender(val);
  document.getElementById('mp_kk_drop').style.display = 'block';
}

function kkSelect(id, nomor) {
  document.getElementById('mp_kk').value       = id;
  document.getElementById('mp_kk_text').value  = nomor;
  document.getElementById('mp_kk_clear').style.display = 'block';
  document.getElementById('mp_kk_drop').style.display  = 'none';
}

function kkClear() {
  document.getElementById('mp_kk').value       = '';
  document.getElementById('mp_kk_text').value  = '';
  document.getElementById('mp_kk_clear').style.display = 'none';
  document.getElementById('mp_kk_drop').style.display  = 'none';
}

document.getElementById('mp_kk_text').addEventListener('blur', () => {
  setTimeout(() => { document.getElementById('mp_kk_drop').style.display = 'none'; }, 200);
});

/* ── Penduduk Modal ─────────────────────────────────────────────────────── */
function openPendudukModal(data = null) {
  document.getElementById('mpTitle').textContent = data ? 'Edit Data Penduduk' : 'Tambah Penduduk';
  document.getElementById('mpMethod').innerHTML  = data ? '<input type="hidden" name="_method" value="PUT">' : '';
  document.getElementById('fmPenduduk').action   = data ? BASE_URL + data.id : STORE_URL;

  const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
  set('mp_id',         data?.id ?? '');
  set('mp_nik',        data?.nik ?? '');
  set('mp_nama',       data?.nama_lengkap ?? '');
  set('mp_tempat',     data?.tempat_lahir ?? '');
  set('mp_tgl',        data?.tanggal_lahir?.substring(0, 10) ?? '');
  set('mp_jk',         data?.jenis_kelamin ?? '');
  set('mp_agama',      data?.agama ?? '');
  set('mp_sp',         data?.status_perkawinan ?? '');
  set('mp_pekerjaan',  data?.pekerjaan ?? '');
  set('mp_pendidikan', data?.pendidikan ?? '');
  set('mp_gd',         data?.golongan_darah ?? '');
  set('mp_hub',        data?.hubungan_keluarga ?? 'kepala_keluarga');
  set('mp_statuspend', data?.status_penduduk ?? 'tetap');
  set('mp_wni',        data?.kewarganegaraan ?? 'WNI');

  // KK combobox
  const kkId   = data?.kk_id ?? null;
  const kkItem = kkId ? KK_DATA.find(k => k.id == kkId) : null;
  document.getElementById('mp_kk').value                    = kkId ?? '';
  document.getElementById('mp_kk_text').value               = kkItem ? kkItem.nomor_kk : '';
  document.getElementById('mp_kk_clear').style.display      = kkItem ? 'block' : 'none';
  document.getElementById('mp_kk_drop').style.display       = 'none';

  document.getElementById('modalPenduduk').style.display = 'block';
}
function closePendudukModal() { document.getElementById('modalPenduduk').style.display = 'none'; }

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePendudukModal(); });

@if($errors->any())
// Buka ulang modal dengan nilai lama setelah validasi gagal
(function() {
  const oldMethod = @json(old('_method', ''));
  const oldData = {
    id:                 @json(old('_id', '')),
    nik:                @json(old('nik', '')),
    nama_lengkap:       @json(old('nama_lengkap', '')),
    tempat_lahir:       @json(old('tempat_lahir', '')),
    tanggal_lahir:      @json(old('tanggal_lahir', '')),
    jenis_kelamin:      @json(old('jenis_kelamin', '')),
    agama:              @json(old('agama', '')),
    status_perkawinan:  @json(old('status_perkawinan', '')),
    pekerjaan:          @json(old('pekerjaan', '')),
    pendidikan:         @json(old('pendidikan', '')),
    golongan_darah:     @json(old('golongan_darah', '')),
    hubungan_keluarga:  @json(old('hubungan_keluarga', 'kepala_keluarga')),
    status_penduduk:    @json(old('status_penduduk', 'tetap')),
    kewarganegaraan:    @json(old('kewarganegaraan', '')),
    kk_id:              @json(old('kk_id', '')),
  };

  // Jika ini edit (ada _method PUT), set action ke URL edit
  if (oldMethod === 'PUT' && oldData.id) {
    document.getElementById('fmPenduduk').action = BASE_URL + oldData.id;
    document.getElementById('mpMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('mpTitle').textContent = 'Edit Data Penduduk';
  }

  openPendudukModal(oldMethod === 'PUT' ? oldData : null);

  // Isi ulang field yang tidak di-handle openPendudukModal untuk kasus tambah baru
  if (oldMethod !== 'PUT') {
    const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
    set('mp_nik',        oldData.nik);
    set('mp_nama',       oldData.nama_lengkap);
    set('mp_tempat',     oldData.tempat_lahir);
    set('mp_tgl',        oldData.tanggal_lahir);
    set('mp_jk',         oldData.jenis_kelamin);
    set('mp_agama',      oldData.agama);
    set('mp_sp',         oldData.status_perkawinan);
    set('mp_pekerjaan',  oldData.pekerjaan);
    set('mp_pendidikan', oldData.pendidikan);
    set('mp_gd',         oldData.golongan_darah);
    set('mp_hub',        oldData.hubungan_keluarga);
    set('mp_statuspend', oldData.status_penduduk);
    set('mp_wni',        oldData.kewarganegaraan);

    if (oldData.kk_id) {
      const kkItem = KK_DATA.find(k => k.id == oldData.kk_id);
      document.getElementById('mp_kk').value       = oldData.kk_id;
      document.getElementById('mp_kk_text').value  = kkItem ? kkItem.nomor_kk : '';
      document.getElementById('mp_kk_clear').style.display = 'block';
    }
  }
})();
@endif
</script>
@endsection
