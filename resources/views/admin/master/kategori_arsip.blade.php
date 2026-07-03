@extends('layouts.admin')

@section('title', 'Kategori Arsip')
@section('page-title', 'Kategori Arsip')
@section('page-sub', 'Master data kategori dokumen arsip')

@section('content')

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

  {{-- ── Kiri: Daftar Kategori Arsip ──────────────── --}}
  <div class="lg:col-span-2 space-y-4">

    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-base">Daftar Kategori Arsip</h2>
      <span class="text-xs text-slate-400">{{ $kategoris->count() }} kategori</span>
    </div>

    @forelse($kategoris as $kat)
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-3 flex-1 min-w-0">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center mt-0.5 {{ !$kat->warna ? 'bg-slate-100 dark:bg-slate-800' : '' }}"
                 style="{{ $kat->warna ? 'background:' . $kat->warna . '22' : '' }}">
              <i class="ti ti-folder" style="{{ $kat->warna ? 'color:' . $kat->warna : '' }}"></i>
            </div>
            <div class="min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $kat->nama }}</span>
                <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-md">/{{ $kat->slug }}</code>
                @if($kat->warna)
                  <span class="inline-flex items-center gap-1 text-xs text-slate-400">
                    <span class="w-3 h-3 rounded-full border border-slate-200 dark:border-slate-700" style="background:{{ $kat->warna }}"></span>
                    {{ $kat->warna }}
                  </span>
                @endif
              </div>
              @if($kat->deskripsi)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $kat->deskripsi }}</p>
              @endif
              <div class="flex items-center gap-1.5 mt-1.5 text-xs text-slate-400">
                <i class="ti ti-archive text-sm"></i>
                <span>{{ $kat->arsips_count }} dokumen arsip</span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 flex-shrink-0">
            <button type="button"
              onclick="openEdit({{ $kat->id }}, @js($kat->nama), @js($kat->slug), @js($kat->warna ?? ''), @js($kat->deskripsi ?? ''))"
              class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
              <i class="ti ti-edit text-sm"></i> Edit
            </button>
            <form method="POST" action="{{ route('admin.master.kategoriArsip.destroy', $kat) }}" class="m-0">
              @csrf @method('DELETE')
              <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Kategori \'{{ addslashes($kat->nama) }}\' akan dihapus. Arsip yang terhubung tidak ikut terhapus.', 'Hapus Kategori Arsip')"
                class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                <i class="ti ti-trash text-sm"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-12 text-center">
        <i class="ti ti-folder-off text-3xl text-slate-300 dark:text-slate-600 block mb-2"></i>
        <p class="text-sm text-slate-400">Belum ada kategori arsip. Buat kategori pertama di panel kanan.</p>
      </div>
    @endforelse
  </div>

  {{-- ── Kanan: Form ───────────────────────────────── --}}
  <div class="space-y-4">
    <h2 class="font-semibold text-base" id="formTitle">Tambah Kategori Arsip</h2>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <form method="POST" id="kategoriForm" action="{{ route('admin.master.kategoriArsip.store') }}" class="space-y-4">
        @csrf
        <div id="methodField"></div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
          <input type="text" name="nama" id="f_nama" required
            class="w-full px-3 h-9 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('nama') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
            value="{{ old('nama') }}" placeholder="Contoh: Peraturan Desa">
          @error('nama')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Slug URL</label>
          <div class="flex items-center rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden focus-within:ring-2 focus-within:ring-brand-500">
            <span class="px-2.5 h-9 flex items-center text-slate-400 text-xs border-r border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex-shrink-0">/</span>
            <input type="text" name="slug" id="f_slug"
              class="flex-1 min-w-0 px-3 h-9 bg-white dark:bg-slate-800 text-sm outline-none border-0 focus:ring-0 {{ $errors->has('slug') ? 'text-rose-600' : '' }}"
              value="{{ old('slug') }}" placeholder="peraturan-desa" pattern="[a-z0-9-]+">
          </div>
          <p class="text-xs text-slate-400 mt-1">Diisi otomatis dari nama. Huruf kecil &amp; tanda hubung saja.</p>
          @error('slug')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Warna Label <span class="text-slate-400 font-normal">(opsional)</span></label>
          <div class="flex items-center gap-2">
            <input type="color" name="warna" id="f_warna"
              class="h-9 w-16 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer p-1 bg-white dark:bg-slate-800"
              value="{{ old('warna', '#059669') }}">
            <input type="text" id="f_warnaHex"
              class="flex-1 min-w-0 px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono outline-none focus:ring-2 focus:ring-brand-500"
              value="{{ old('warna', '#059669') }}" placeholder="#059669" maxlength="7">
            <button type="button" onclick="clearWarna()"
              class="px-2.5 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-xs hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-slate-400"
              title="Hapus warna">✕</button>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
          <textarea name="deskripsi" id="f_deskripsi" rows="3"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Penjelasan singkat kategori ini…">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-2 pt-1">
          <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-plus text-base" id="btnIcon"></i>
            <span id="btnLabel">Tambah Kategori</span>
          </button>
          <button type="button" id="cancelBtn" onclick="resetForm()" style="display:none"
            class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

@endsection

@section('scripts')
<script>
const STORE_URL   = '{{ route('admin.master.kategoriArsip.store') }}';
const UPDATE_BASE = '{{ url('admin/master/kategori-arsip') }}/';

document.getElementById('f_nama').addEventListener('input', function () {
  const slugField = document.getElementById('f_slug');
  if (slugField.dataset.manual === '1') return;
  slugField.value = this.value
    .toLowerCase()
    .normalize('NFD').replace(/[̀-ͯ]/g, '')
    .replace(/[^a-z0-9\s-]/g, '')
    .trim().replace(/\s+/g, '-');
});
document.getElementById('f_slug').addEventListener('input', function () {
  this.dataset.manual = this.value ? '1' : '';
});

const colorInput = document.getElementById('f_warna');
const hexInput   = document.getElementById('f_warnaHex');
colorInput.addEventListener('input', () => { hexInput.value = colorInput.value; });
hexInput.addEventListener('input', function () {
  if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) colorInput.value = this.value;
});
function clearWarna() {
  colorInput.value = '#059669';
  hexInput.value = '';
  colorInput.name = '';
}

function openEdit(id, nama, slug, warna, deskripsi) {
  document.getElementById('formTitle').textContent      = 'Edit Kategori Arsip';
  document.getElementById('f_nama').value               = nama;
  document.getElementById('f_slug').value               = slug;
  document.getElementById('f_slug').dataset.manual      = '1';
  document.getElementById('f_warna').value              = warna || '#059669';
  document.getElementById('f_warnaHex').value           = warna || '';
  document.getElementById('f_deskripsi').value          = deskripsi;
  document.getElementById('methodField').innerHTML      = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('kategoriForm').action        = UPDATE_BASE + id;
  document.getElementById('btnIcon').className          = 'ti ti-device-floppy text-base';
  document.getElementById('btnLabel').textContent       = 'Simpan Perubahan';
  document.getElementById('cancelBtn').style.display   = '';
  document.getElementById('f_nama').scrollIntoView({ behavior: 'smooth', block: 'center' });
  document.getElementById('f_nama').focus();
}

function resetForm() {
  document.getElementById('formTitle').textContent      = 'Tambah Kategori Arsip';
  document.getElementById('kategoriForm').reset();
  document.getElementById('kategoriForm').action        = STORE_URL;
  document.getElementById('methodField').innerHTML      = '';
  document.getElementById('f_slug').dataset.manual      = '';
  document.getElementById('btnIcon').className          = 'ti ti-plus text-base';
  document.getElementById('btnLabel').textContent       = 'Tambah Kategori';
  document.getElementById('cancelBtn').style.display   = 'none';
  colorInput.name = 'warna';
}
</script>
@endsection
