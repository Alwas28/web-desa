@extends('layouts.admin')

@section('title', 'Arsip Dokumen')
@section('page-title', 'Arsip')
@section('page-sub', 'Kelola dokumen arsip desa')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-3 gap-3 max-w-lg">
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-archive text-brand-600 dark:text-brand-100"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $total }}</div>
      <div class="text-xs text-slate-400">Total</div>
    </div>
  </div>
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-circle-check text-emerald-600 dark:text-emerald-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $publish }}</div>
      <div class="text-xs text-slate-400">Publish</div>
    </div>
  </div>
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-pencil text-amber-600 dark:text-amber-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $draft }}</div>
      <div class="text-xs text-slate-400">Draft</div>
    </div>
  </div>
</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.arsip.index') }}"
      class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm flex-shrink-0"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>

  <select name="kategori"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    <option value="">Semua Kategori</option>
    @foreach($kategoris as $kat)
      <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
    @endforeach
  </select>

  <select name="status"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    <option value="">Semua Status</option>
    <option value="publish" {{ request('status') === 'publish' ? 'selected' : '' }}>Publish</option>
    <option value="draft"   {{ request('status') === 'draft'   ? 'selected' : '' }}>Draft</option>
  </select>

  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i> Filter
  </button>
  @if(request()->hasAny(['q','status']))
    <a href="{{ route('admin.arsip.index') }}"
       class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
      <i class="ti ti-x text-sm"></i>
    </a>
  @endif

  @if(Auth::user()->hasPermission('tambah.arsip'))
  <button type="button" onclick="openArsipModal()"
    class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors flex-shrink-0">
    <i class="ti ti-plus text-base"></i> Tambah Arsip
  </button>
  @endif
</form>

@if(session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
  <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
</div>
@endif

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($arsips->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-archive-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400 mb-4">
        {{ request()->hasAny(['q','status']) ? 'Tidak ada arsip yang sesuai filter.' : 'Belum ada dokumen arsip.' }}
      </p>
      @unless(request()->hasAny(['q','status']))
      <button type="button" onclick="openArsipModal()"
        class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
        <i class="ti ti-plus text-base"></i> Tambah Arsip Pertama
      </button>
      @endunless
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
            <th class="px-4 py-3 w-10"></th>
            <th class="px-4 py-3">Dokumen</th>
            <th class="px-4 py-3">Kategori</th>
            <th class="px-4 py-3">Ukuran</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($arsips as $a)
            @php
              $ext = $a->extension;
              $iconClass = match(true) {
                  in_array($ext, ['pdf'])               => 'ti-file-type-pdf text-rose-500',
                  in_array($ext, ['doc','docx'])        => 'ti-file-word text-blue-500',
                  in_array($ext, ['xls','xlsx'])        => 'ti-file-spreadsheet text-emerald-500',
                  in_array($ext, ['ppt','pptx'])        => 'ti-file-presentation text-orange-500',
                  in_array($ext, ['zip','rar'])         => 'ti-file-zip text-purple-500',
                  default                               => 'ti-file text-slate-400',
              };
            @endphp
            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">

              {{-- Ikon file --}}
              <td class="px-4 py-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 grid place-items-center">
                  <i class="ti {{ $iconClass }} text-lg"></i>
                </div>
              </td>

              {{-- Judul + deskripsi --}}
              <td class="px-4 py-3 max-w-xs">
                <div class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $a->judul }}</div>
                @if($a->deskripsi)
                  <div class="text-xs text-slate-400 truncate mt-0.5">{{ Str::limit($a->deskripsi, 60) }}</div>
                @endif
                <div class="text-[10px] font-mono text-slate-300 dark:text-slate-600 mt-0.5 uppercase">{{ $ext }}</div>
              </td>

              {{-- Kategori --}}
              <td class="px-4 py-3">
                @if($a->kategoriArsip)
                  <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full"
                    style="{{ $a->kategoriArsip->warna ? 'background:' . $a->kategoriArsip->warna . '22;color:' . $a->kategoriArsip->warna : '' }}"
                    class="{{ !$a->kategoriArsip->warna ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' : '' }}">
                    {{ $a->kategoriArsip->nama }}
                  </span>
                @else
                  <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                @endif
              </td>

              {{-- Ukuran --}}
              <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                {{ $a->ukuran_format }}
              </td>

              {{-- Status --}}
              <td class="px-4 py-3">
                @if($a->status === 'publish')
                  <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Publish
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Draft
                  </span>
                @endif
              </td>

              {{-- Tanggal --}}
              <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                {{ ($a->published_at ?? $a->created_at)->format('d M Y') }}
              </td>

              {{-- Aksi --}}
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="{{ $a->file_url }}" target="_blank"
                    class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                    title="Download">
                    <i class="ti ti-download text-sm"></i>
                  </a>
                  @if(Auth::user()->hasPermission('edit.arsip'))
                  <button type="button"
                    onclick="openArsipModal(@js(['id'=>$a->id,'judul'=>$a->judul,'kategori_arsip_id'=>$a->kategori_arsip_id,'deskripsi'=>$a->deskripsi,'status'=>$a->status,'nama_file'=>$a->nama_file]))"
                    class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <i class="ti ti-edit text-sm"></i>
                  </button>
                  @endif
                  @if(Auth::user()->hasPermission('hapus.arsip'))
                  <form method="POST" action="{{ route('admin.arsip.destroy', $a) }}" class="m-0">
                    @csrf @method('DELETE')
                    <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Arsip ini akan dihapus permanen.', 'Hapus Arsip')"
                      class="flex items-center px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($arsips->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $arsips->links() }}
      </div>
    @endif
  @endif
</div>

{{-- ══════════════════════════════════════════════════
     MODAL ARSIP
══════════════════════════════════════════════════ --}}
<div id="arsipModal" style="display:none"
  class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeArsipModal()"></div>
  <div class="relative z-10 w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-base" id="arsipModalTitle">Tambah Arsip</h2>
      <button type="button" onclick="closeArsipModal()"
        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-400">
        <i class="ti ti-x"></i>
      </button>
    </div>

    {{-- Form --}}
    <form id="arsipForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
      @csrf
      <div id="arsipMethodField"></div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Judul <span class="text-rose-500">*</span></label>
        <input type="text" name="judul" id="af_judul" required
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
          placeholder="Nama dokumen arsip">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Kategori <span class="text-slate-400 font-normal">(opsional)</span></label>
        <select name="kategori_arsip_id" id="af_kategori"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
          <option value="">-- Tanpa Kategori --</option>
          @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
        <textarea name="deskripsi" id="af_deskripsi" rows="2"
          class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
          placeholder="Keterangan singkat dokumen…"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1.5">
          File Dokumen
          <span id="af_file_hint" class="text-slate-400 font-normal ml-1">(PDF, Word, Excel, PPT, ZIP · maks. 10 MB)</span>
        </label>
        <div id="af_current_file" style="display:none" class="mb-2 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
          <i class="ti ti-file-check text-sm"></i>
          <span id="af_current_name"></span>
          <span class="text-slate-300 dark:text-slate-600">— ganti dengan upload baru (opsional)</span>
        </div>
        <input type="file" name="file" id="af_file"
          accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
          class="w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-500/10 dark:file:text-brand-300 transition-colors">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1.5">Status</label>
        <select name="status" id="af_status"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
          <option value="draft">Draft — hanya terlihat di admin</option>
          <option value="publish">Publish — tampil di halaman publik</option>
        </select>
      </div>

      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeArsipModal()"
          class="flex-1 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit"
          class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-device-floppy text-base" id="af_btn_icon"></i>
          <span id="af_btn_label">Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
const ARSIP_STORE_URL  = '{{ route('admin.arsip.store') }}';
const ARSIP_UPDATE_BASE = '{{ url('admin/arsip') }}/';

function openArsipModal(data = null) {
  const modal = document.getElementById('arsipModal');
  const form  = document.getElementById('arsipForm');

  form.reset();
  document.getElementById('arsipMethodField').innerHTML = '';
  document.getElementById('af_current_file').style.display = 'none';

  if (data) {
    document.getElementById('arsipModalTitle').textContent = 'Edit Arsip';
    document.getElementById('af_judul').value             = data.judul ?? '';
    document.getElementById('af_kategori').value          = data.kategori_arsip_id ?? '';
    document.getElementById('af_deskripsi').value         = data.deskripsi ?? '';
    document.getElementById('af_status').value            = data.status ?? 'draft';
    document.getElementById('arsipMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    form.action = ARSIP_UPDATE_BASE + data.id;

    if (data.nama_file) {
      const el = document.getElementById('af_current_file');
      el.style.display = 'flex';
      document.getElementById('af_current_name').textContent = data.nama_file;
    }

    document.getElementById('af_btn_icon').className  = 'ti ti-device-floppy text-base';
    document.getElementById('af_btn_label').textContent = 'Simpan Perubahan';
  } else {
    document.getElementById('arsipModalTitle').textContent  = 'Tambah Arsip';
    form.action = ARSIP_STORE_URL;
    document.getElementById('af_btn_icon').className   = 'ti ti-plus text-base';
    document.getElementById('af_btn_label').textContent = 'Tambah Arsip';
  }

  modal.style.display = 'flex';
}

function closeArsipModal() {
  document.getElementById('arsipModal').style.display = 'none';
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeArsipModal();
});
</script>
@endsection
