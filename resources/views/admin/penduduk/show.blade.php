@extends('layouts.admin')

@section('title', 'Profil – '.$penduduk->nama_lengkap)
@section('page-title', 'Profil Penduduk')
@section('page-sub', 'Detail data kependudukan warga')

@section('content')

{{-- Back + Actions --}}
<div class="flex items-center justify-between">
  <a href="{{ route('admin.penduduk.index') }}"
     class="flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
    <i class="ti ti-arrow-left text-base"></i> Kembali ke Data Penduduk
  </a>
  <button type="button"
    onclick="openEditModal()"
    class="flex items-center gap-2 px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-pencil text-sm"></i> Edit Data
  </button>
</div>

{{-- Header Card --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6">
  <div class="flex items-start gap-5">
    {{-- Avatar --}}
    <div class="w-16 h-16 rounded-2xl grid place-items-center flex-shrink-0 text-2xl font-bold
                {{ $penduduk->jenis_kelamin === 'L'
                   ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400'
                   : 'bg-pink-100 dark:bg-pink-500/20 text-pink-600 dark:text-pink-400' }}">
      {{ $penduduk->jenis_kelamin === 'L' ? '♂' : '♀' }}
    </div>
    {{-- Name & badges --}}
    <div class="flex-1 min-w-0">
      <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100 leading-tight">{{ $penduduk->nama_lengkap }}</h1>
      <div class="flex flex-wrap items-center gap-2 mt-2">
        <span class="font-mono text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
          {{ $penduduk->nik }}
        </span>
        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                     {{ $penduduk->jenis_kelamin === 'L'
                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'
                        : 'bg-pink-100 text-pink-700 dark:bg-pink-500/20 dark:text-pink-400' }}">
          {{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
        </span>
        @php
          $umurColors = ['Balita'=>'amber','Anak-anak'=>'yellow','Remaja'=>'sky','Dewasa'=>'emerald','Lansia'=>'violet'];
          $c = $umurColors[$penduduk->kelompok_umur] ?? 'slate';
        @endphp
        <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-500/20 dark:text-{{ $c }}-400">
          {{ $penduduk->kelompok_umur }} · {{ $penduduk->umur }} tahun
        </span>
        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                     {{ $penduduk->status_penduduk === 'tetap'
                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                        : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
          Penduduk {{ ucfirst($penduduk->status_penduduk) }}
        </span>
      </div>
    </div>
  </div>
</div>

{{-- Detail Grid --}}
<div class="grid md:grid-cols-2 gap-5">

  {{-- Data Pribadi --}}
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
      <i class="ti ti-id-badge text-brand-500 text-lg"></i>
      <h2 class="font-semibold text-sm">Data Pribadi</h2>
    </div>
    <dl class="divide-y divide-slate-50 dark:divide-slate-800/50">
      @php
        $rows = [
          ['Tempat / Tgl Lahir', $penduduk->tempat_lahir.', '.$penduduk->tanggal_lahir->translatedFormat('d F Y')],
          ['Agama',              $penduduk->agama],
          ['Status Perkawinan',  $penduduk->status_perkawinan_label],
          ['Pekerjaan',          $penduduk->pekerjaan ?: '—'],
          ['Pendidikan',         $penduduk->pendidikan_label],
          ['Golongan Darah',     $penduduk->golongan_darah ?: '—'],
          ['Kewarganegaraan',    $penduduk->kewarganegaraan],
        ];
      @endphp
      @foreach($rows as [$label, $val])
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">{{ $label }}</dt>
        <dd class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $val }}</dd>
      </div>
      @endforeach
    </dl>
  </div>

  {{-- Data KK --}}
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
      <i class="ti ti-home text-brand-500 text-lg"></i>
      <h2 class="font-semibold text-sm">Data Kependudukan</h2>
    </div>
    <dl class="divide-y divide-slate-50 dark:divide-slate-800/50">
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">Hubungan dalam KK</dt>
        <dd class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $penduduk->hubungan_label }}</dd>
      </div>
      @if($penduduk->kartuKeluarga)
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">No. KK</dt>
        <dd class="text-sm font-mono font-semibold text-slate-700 dark:text-slate-300">
          <a href="{{ route('admin.kk.show', $penduduk->kartuKeluarga) }}"
             class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
            {{ $penduduk->kartuKeluarga->nomor_kk }}
          </a>
        </dd>
      </div>
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">Alamat</dt>
        <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $penduduk->kartuKeluarga->alamat }}</dd>
      </div>
      @if($penduduk->kartuKeluarga->rt || $penduduk->kartuKeluarga->rw)
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">RT / RW</dt>
        <dd class="text-sm text-slate-700 dark:text-slate-300">
          {{ $penduduk->kartuKeluarga->rt ? 'RT '.$penduduk->kartuKeluarga->rt : '' }}
          {{ ($penduduk->kartuKeluarga->rt && $penduduk->kartuKeluarga->rw) ? ' / ' : '' }}
          {{ $penduduk->kartuKeluarga->rw ? 'RW '.$penduduk->kartuKeluarga->rw : '' }}
        </dd>
      </div>
      @endif
      @if($penduduk->kartuKeluarga->dusun)
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">Dusun</dt>
        <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $penduduk->kartuKeluarga->dusun }}</dd>
      </div>
      @endif
      @else
      <div class="flex gap-4 px-5 py-3">
        <dt class="text-xs text-slate-400 w-40 flex-shrink-0 pt-0.5">Kartu Keluarga</dt>
        <dd class="text-sm text-slate-400">Belum terdaftar dalam KK</dd>
      </div>
      @endif
    </dl>
  </div>

</div>

{{-- ══ MODAL EDIT ════════════════════════════════════════════════════════ --}}
<div id="modalEdit" style="display:none"
     class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm overflow-y-auto">
  <div class="flex min-h-full items-center justify-center p-4"
       onclick="if(event.target===this)closeEditModal()">
    <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h2 class="font-semibold text-base">Edit Data Penduduk</h2>
        <button type="button" onclick="closeEditModal()"
          class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <form method="POST" action="{{ route('admin.penduduk.update', $penduduk) }}" class="p-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">

          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1.5">NIK <span class="text-rose-500">*</span></label>
            <input type="text" name="nik" value="{{ $penduduk->nik }}" required maxlength="16" minlength="16"
              inputmode="numeric" pattern="[0-9]{16}"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_lengkap" value="{{ $penduduk->nama_lengkap }}" required maxlength="150"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Tempat Lahir <span class="text-rose-500">*</span></label>
            <input type="text" name="tempat_lahir" value="{{ $penduduk->tempat_lahir }}" required maxlength="100"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
            <input type="date" name="tanggal_lahir" value="{{ $penduduk->tanggal_lahir->format('Y-m-d') }}" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
            <select name="jenis_kelamin" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="L" {{ $penduduk->jenis_kelamin==='L'?'selected':'' }}>Laki-laki</option>
              <option value="P" {{ $penduduk->jenis_kelamin==='P'?'selected':'' }}>Perempuan</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Agama <span class="text-rose-500">*</span></label>
            <select name="agama" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                <option value="{{ $ag }}" {{ $penduduk->agama===$ag?'selected':'' }}>{{ $ag }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Status Perkawinan <span class="text-rose-500">*</span></label>
            <select name="status_perkawinan" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="belum_kawin" {{ $penduduk->status_perkawinan==='belum_kawin'?'selected':'' }}>Belum Kawin</option>
              <option value="kawin"       {{ $penduduk->status_perkawinan==='kawin'?'selected':'' }}>Kawin</option>
              <option value="cerai_hidup" {{ $penduduk->status_perkawinan==='cerai_hidup'?'selected':'' }}>Cerai Hidup</option>
              <option value="cerai_mati"  {{ $penduduk->status_perkawinan==='cerai_mati'?'selected':'' }}>Cerai Mati</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Pekerjaan</label>
            <select name="pekerjaan"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">— Pilih Pekerjaan —</option>
              @foreach(['Tidak Bekerja','Petani','Nelayan','Pedagang','Wiraswasta','PNS','TNI/Polri','Karyawan Swasta','Buruh','Guru/Dosen','Dokter/Tenaga Medis','Pengrajin','Ibu Rumah Tangga','Pelajar/Mahasiswa','Pensiunan','Lainnya'] as $pekerjaan)
                <option value="{{ $pekerjaan }}" {{ $penduduk->pekerjaan===$pekerjaan?'selected':'' }}>{{ $pekerjaan }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Pendidikan <span class="text-rose-500">*</span></label>
            <select name="pendidikan" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="tidak_sekolah" {{ $penduduk->pendidikan==='tidak_sekolah'?'selected':'' }}>Tidak Sekolah</option>
              <option value="sd"            {{ $penduduk->pendidikan==='sd'?'selected':'' }}>SD</option>
              <option value="smp"           {{ $penduduk->pendidikan==='smp'?'selected':'' }}>SMP</option>
              <option value="sma"           {{ $penduduk->pendidikan==='sma'?'selected':'' }}>SMA</option>
              <option value="diploma"       {{ $penduduk->pendidikan==='diploma'?'selected':'' }}>Diploma</option>
              <option value="s1"            {{ $penduduk->pendidikan==='s1'?'selected':'' }}>Sarjana (S1)</option>
              <option value="s2"            {{ $penduduk->pendidikan==='s2'?'selected':'' }}>Magister (S2)</option>
              <option value="s3"            {{ $penduduk->pendidikan==='s3'?'selected':'' }}>Doktor (S3)</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Golongan Darah</label>
            <select name="golongan_darah"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="">Tidak Tahu</option>
              @foreach(['A','B','AB','O'] as $gd)
                <option value="{{ $gd }}" {{ $penduduk->golongan_darah===$gd?'selected':'' }}>{{ $gd }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Hubungan dalam KK <span class="text-rose-500">*</span></label>
            <select name="hubungan_keluarga" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="kepala_keluarga" {{ $penduduk->hubungan_keluarga==='kepala_keluarga'?'selected':'' }}>Kepala Keluarga</option>
              <option value="istri"           {{ $penduduk->hubungan_keluarga==='istri'?'selected':'' }}>Istri</option>
              <option value="anak"            {{ $penduduk->hubungan_keluarga==='anak'?'selected':'' }}>Anak</option>
              <option value="orang_tua"       {{ $penduduk->hubungan_keluarga==='orang_tua'?'selected':'' }}>Orang Tua</option>
              <option value="mertua"          {{ $penduduk->hubungan_keluarga==='mertua'?'selected':'' }}>Mertua</option>
              <option value="famili_lain"     {{ $penduduk->hubungan_keluarga==='famili_lain'?'selected':'' }}>Famili Lain</option>
              <option value="lainnya"         {{ $penduduk->hubungan_keluarga==='lainnya'?'selected':'' }}>Lainnya</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Status Penduduk <span class="text-rose-500">*</span></label>
            <select name="status_penduduk" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="tetap"    {{ $penduduk->status_penduduk==='tetap'?'selected':'' }}>Tetap</option>
              <option value="sementara"{{ $penduduk->status_penduduk==='sementara'?'selected':'' }}>Sementara</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Kewarganegaraan <span class="text-rose-500">*</span></label>
            <select name="kewarganegaraan" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm outline-none focus:ring-2 focus:ring-brand-500">
              <option value="WNI" {{ $penduduk->kewarganegaraan==='WNI'?'selected':'' }}>WNI</option>
              <option value="WNA" {{ $penduduk->kewarganegaraan==='WNA'?'selected':'' }}>WNA</option>
            </select>
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-medium mb-1.5">Kartu Keluarga</label>
            <input type="hidden" name="kk_id" id="ep_kk">
            <div class="relative">
              <div class="flex items-center h-9 px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus-within:ring-2 focus-within:ring-brand-500 focus-within:border-transparent">
                <i class="ti ti-home text-slate-400 text-sm flex-shrink-0 mr-2"></i>
                <input type="text" id="ep_kk_text" placeholder="Cari nomor KK…" autocomplete="off"
                  class="flex-1 bg-transparent text-sm outline-none border-0 p-0 min-w-0"
                  oninput="kkSearchEdit(this.value)" onfocus="kkOpenEdit()">
                <button type="button" id="ep_kk_clear" onclick="kkClearEdit()" style="display:none"
                  class="ml-1 text-slate-300 hover:text-slate-500 flex-shrink-0">
                  <i class="ti ti-x text-xs"></i>
                </button>
              </div>
              <div id="ep_kk_drop" style="display:none"
                class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-xl z-[60] max-h-52 overflow-y-auto">
              </div>
            </div>
          </div>

        </div>

        <div class="mt-8 pt-5 border-t border-slate-100 dark:border-slate-800 flex gap-3">
          <button type="button" onclick="closeEditModal()"
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
const KK_DATA = @js(\App\Models\KartuKeluarga::orderBy('nomor_kk')->get(['id','nomor_kk','alamat']));

/* ── Searchable KK Combobox (edit modal) ───────────────────────────────── */
function kkRenderEdit(search) {
  const drop = document.getElementById('ep_kk_drop');
  const q    = (search || '').toLowerCase();
  const hits = KK_DATA.filter(k =>
    k.nomor_kk.includes(q) || (k.alamat && k.alamat.toLowerCase().includes(q))
  ).slice(0, 60);
  if (!hits.length) {
    drop.innerHTML = '<div class="px-4 py-3 text-sm text-slate-400">Tidak ditemukan</div>';
    return;
  }
  drop.innerHTML = hits.map(k => `
    <button type="button"
      class="w-full text-left px-4 py-2.5 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
      onmousedown="kkSelectEdit(${k.id},'${k.nomor_kk}')">
      <div class="font-mono font-semibold text-slate-900 dark:text-slate-100 text-xs">${k.nomor_kk}</div>
      <div class="text-xs text-slate-400 truncate">${k.alamat ?? ''}</div>
    </button>
  `).join('');
}
function kkOpenEdit() {
  kkRenderEdit(document.getElementById('ep_kk_text').value);
  document.getElementById('ep_kk_drop').style.display = 'block';
}
function kkSearchEdit(val) {
  document.getElementById('ep_kk').value = '';
  kkRenderEdit(val);
  document.getElementById('ep_kk_drop').style.display = 'block';
}
function kkSelectEdit(id, nomor) {
  document.getElementById('ep_kk').value       = id;
  document.getElementById('ep_kk_text').value  = nomor;
  document.getElementById('ep_kk_clear').style.display = 'block';
  document.getElementById('ep_kk_drop').style.display  = 'none';
}
function kkClearEdit() {
  document.getElementById('ep_kk').value       = '';
  document.getElementById('ep_kk_text').value  = '';
  document.getElementById('ep_kk_clear').style.display = 'none';
  document.getElementById('ep_kk_drop').style.display  = 'none';
}
document.getElementById('ep_kk_text').addEventListener('blur', () => {
  setTimeout(() => { document.getElementById('ep_kk_drop').style.display = 'none'; }, 200);
});

/* ── Modal open/close ──────────────────────────────────────────────────── */
function openEditModal() {
  const kkId   = {{ $penduduk->kk_id ?? 'null' }};
  const kkItem = kkId ? KK_DATA.find(k => k.id === kkId) : null;
  document.getElementById('ep_kk').value                    = kkId ?? '';
  document.getElementById('ep_kk_text').value               = kkItem ? kkItem.nomor_kk : '';
  document.getElementById('ep_kk_clear').style.display      = kkItem ? 'block' : 'none';
  document.getElementById('ep_kk_drop').style.display       = 'none';
  document.getElementById('modalEdit').style.display = 'block';
}
function closeEditModal() { document.getElementById('modalEdit').style.display = 'none'; }

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditModal(); });
</script>
@endsection
