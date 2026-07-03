@extends('layouts.admin')

@section('title', 'Jabatan')
@section('page-title', 'Jabatan')
@section('page-sub', 'Master data jabatan perangkat desa')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

  {{-- ── Kiri: Daftar Jabatan ──────────────────────── --}}
  <div class="lg:col-span-2 space-y-4">

    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-base">Daftar Jabatan</h2>
      <span class="text-xs text-slate-400">{{ $jabatans->count() }} jabatan</span>
    </div>

    @forelse($jabatans as $jab)
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-3 flex-1 min-w-0">
            {{-- Urutan badge --}}
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center">
              <span class="text-xs font-bold text-brand-700 dark:text-brand-100">{{ $jab->urutan }}</span>
            </div>
            <div class="min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $jab->nama }}</span>
                @if($jab->singkatan)
                  <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-md">{{ $jab->singkatan }}</code>
                @endif
              </div>
              @if($jab->deskripsi)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $jab->deskripsi }}</p>
              @endif
            </div>
          </div>

          <div class="flex items-center gap-2 flex-shrink-0">
            @if(Auth::user()->hasPermission('edit.jabatan'))
            <button type="button"
              onclick="openEdit({{ $jab->id }}, @js($jab->nama), @js($jab->singkatan ?? ''), @js($jab->deskripsi ?? ''), {{ $jab->urutan }})"
              class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
              <i class="ti ti-edit text-sm"></i> Edit
            </button>
            @endif
            @if(Auth::user()->hasPermission('hapus.jabatan'))
            <form method="POST" action="{{ route('admin.master.jabatan.destroy', $jab) }}" class="m-0">
              @csrf @method('DELETE')
              <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Jabatan \'{{ addslashes($jab->nama) }}\' akan dihapus permanen.', 'Hapus Jabatan')"
                class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                <i class="ti ti-trash text-sm"></i>
              </button>
            </form>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-12 text-center">
        <i class="ti ti-id-badge-off text-3xl text-slate-300 dark:text-slate-600 block mb-2"></i>
        <p class="text-sm text-slate-400">Belum ada jabatan. Tambah jabatan pertama di panel kanan.</p>
      </div>
    @endforelse
  </div>

  {{-- ── Kanan: Form ───────────────────────────────── --}}
  @if(Auth::user()->hasPermission('tambah.jabatan') || Auth::user()->hasPermission('edit.jabatan'))
  <div class="space-y-4">
    <h2 class="font-semibold text-base" id="formTitle">Tambah Jabatan</h2>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <form method="POST" id="jabatanForm" action="{{ route('admin.master.jabatan.store') }}" class="space-y-4">
        @csrf
        <div id="methodField"></div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Nama Jabatan <span class="text-rose-500">*</span></label>
          <input type="text" name="nama" id="f_nama" required
            class="w-full px-3 h-9 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('nama') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
            value="{{ old('nama') }}" placeholder="Contoh: Kepala Desa">
          @error('nama')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium mb-1.5">Singkatan</label>
            <input type="text" name="singkatan" id="f_singkatan"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('singkatan') }}" placeholder="KADES" maxlength="20">
            @error('singkatan')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Urutan Tampil</label>
            <input type="number" name="urutan" id="f_urutan" min="0" max="9999"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('urutan', 0) }}" placeholder="0">
            <p class="text-xs text-slate-400 mt-1">0 = paling atas</p>
            @error('urutan')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
          <textarea name="deskripsi" id="f_deskripsi" rows="3"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Tugas dan tanggung jawab jabatan…">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-2 pt-1">
          <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-plus text-base" id="btnIcon"></i>
            <span id="btnLabel">Tambah Jabatan</span>
          </button>
          <button type="button" id="cancelBtn" onclick="resetForm()" style="display:none"
            class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>

    {{-- Tips urutan --}}
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4">
      <h3 class="text-sm font-medium mb-2">Panduan Urutan</h3>
      <p class="text-xs text-slate-400 leading-relaxed">
        Jabatan diurutkan dari angka terkecil ke terbesar. Contoh:
        <span class="font-mono text-brand-600 dark:text-brand-100">0</span> = Kepala Desa (tertinggi),
        <span class="font-mono text-brand-600 dark:text-brand-100">1</span> = Sekretaris,
        <span class="font-mono text-brand-600 dark:text-brand-100">2</span> = Bendahara, dst.
      </p>
    </div>
  </div>
  @endif
</div>

@endsection

@section('scripts')
<script>
const STORE_URL   = '{{ route('admin.master.jabatan.store') }}';
const UPDATE_BASE = '{{ url('admin/master/jabatan') }}/';

function openEdit(id, nama, singkatan, deskripsi, urutan) {
  document.getElementById('formTitle').textContent = 'Edit Jabatan';
  document.getElementById('f_nama').value      = nama;
  document.getElementById('f_singkatan').value = singkatan;
  document.getElementById('f_deskripsi').value = deskripsi;
  document.getElementById('f_urutan').value    = urutan;
  document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('jabatanForm').action = UPDATE_BASE + id;
  document.getElementById('btnIcon').className  = 'ti ti-device-floppy text-base';
  document.getElementById('btnLabel').textContent = 'Simpan Perubahan';
  document.getElementById('cancelBtn').style.display = '';
  document.getElementById('f_nama').scrollIntoView({ behavior:'smooth', block:'center' });
  document.getElementById('f_nama').focus();
}

function resetForm() {
  document.getElementById('formTitle').textContent = 'Tambah Jabatan';
  document.getElementById('jabatanForm').reset();
  document.getElementById('jabatanForm').action   = STORE_URL;
  document.getElementById('methodField').innerHTML = '';
  document.getElementById('f_urutan').value = 0;
  document.getElementById('btnIcon').className  = 'ti ti-plus text-base';
  document.getElementById('btnLabel').textContent = 'Tambah Jabatan';
  document.getElementById('cancelBtn').style.display = 'none';
}
</script>
@endsection
