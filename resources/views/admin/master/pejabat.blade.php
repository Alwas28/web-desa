@extends('layouts.admin')

@section('title', 'Pejabat Desa')
@section('page-title', 'Pejabat Desa')
@section('page-sub', 'Data pejabat dan perangkat desa')

@section('content')

{{-- Toolbar --}}
<div class="flex items-center justify-between gap-3">
  <div class="flex items-center gap-2">
    <span class="text-sm text-slate-500 dark:text-slate-400">
      <b class="text-slate-900 dark:text-slate-100">{{ $pejabats->count() }}</b> pejabat tercatat
    </span>
  </div>
  @if(Auth::user()->hasPermission('tambah.pejabat'))
  <button type="button" onclick="openModal()"
    class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus text-base"></i> Tambah Pejabat
  </button>
  @endif
</div>

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($pejabats->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-user-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400 mb-4">Belum ada data pejabat.</p>
      @if(Auth::user()->hasPermission('tambah.pejabat'))
      <button type="button" onclick="openModal()"
        class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
        <i class="ti ti-plus text-base"></i> Tambah Pejabat Pertama
      </button>
      @endif
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
            <th class="px-5 py-3 w-14"></th>
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Jabatan</th>
            <th class="px-4 py-3">Periode</th>
            <th class="px-4 py-3">NIP</th>
            <th class="px-4 py-3">Kontak</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($pejabats as $pej)
            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">

              {{-- Foto --}}
              <td class="px-5 py-3">
                @if($pej->foto_url)
                  <img src="{{ $pej->foto_url }}" alt="{{ $pej->nama }}"
                    class="w-9 h-9 rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                @else
                  <div class="w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center border border-brand-100 dark:border-brand-500/20 flex-shrink-0">
                    <span class="text-xs font-bold text-brand-600 dark:text-brand-100 leading-none">
                      {{ strtoupper(substr($pej->nama, 0, 2)) }}
                    </span>
                  </div>
                @endif
              </td>

              {{-- Nama --}}
              <td class="px-4 py-3">
                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $pej->nama }}</div>
                @if($pej->pendidikan)
                  <div class="text-xs text-slate-400 mt-0.5">{{ $pej->pendidikan }}</div>
                @endif
              </td>

              {{-- Jabatan --}}
              <td class="px-4 py-3">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-100">
                  <i class="ti ti-id-badge text-sm"></i>
                  {{ $pej->jabatan->nama }}
                  @if($pej->jabatan->singkatan)
                    <span class="opacity-60">({{ $pej->jabatan->singkatan }})</span>
                  @endif
                </span>
              </td>

              {{-- Periode --}}
              <td class="px-4 py-3">
                <span class="text-xs text-slate-600 dark:text-slate-300">{{ $pej->periode->nama }}</span>
                @php $aktif = is_null($pej->periode->selesai) || $pej->periode->selesai->isFuture(); @endphp
                @if($aktif)
                  <span class="ml-1.5 inline-flex items-center gap-1 text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Aktif
                  </span>
                @endif
              </td>

              {{-- NIP --}}
              <td class="px-4 py-3">
                @if($pej->nip)
                  <code class="text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $pej->nip }}</code>
                @else
                  <span class="text-slate-300 dark:text-slate-600">—</span>
                @endif
              </td>

              {{-- Kontak --}}
              <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                @if($pej->no_hp)
                  <span class="flex items-center gap-1">
                    <i class="ti ti-phone text-sm"></i>{{ $pej->no_hp }}
                  </span>
                @else
                  <span class="text-slate-300 dark:text-slate-600">—</span>
                @endif
              </td>

              {{-- Aksi --}}
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-1.5">
                  @if(Auth::user()->hasPermission('edit.pejabat'))
                  <button type="button"
                    onclick="openEdit(
                      {{ $pej->id }},
                      {{ $pej->jabatan_id }},
                      {{ $pej->periode_id }},
                      @js($pej->nama),
                      @js($pej->nip ?? ''),
                      @js($pej->tempat_lahir ?? ''),
                      @js($pej->tanggal_lahir?->format('Y-m-d') ?? ''),
                      @js($pej->pendidikan ?? ''),
                      @js($pej->no_hp ?? ''),
                      @js($pej->alamat ?? ''),
                      @js($pej->foto_url ?? '')
                    )"
                    class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <i class="ti ti-edit text-sm"></i> Edit
                  </button>
                  @endif
                  @if(Auth::user()->hasPermission('hapus.pejabat'))
                  <form method="POST" action="{{ route('admin.master.pejabat.destroy', $pej) }}" class="m-0">
                    @csrf @method('DELETE')
                    <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Data pejabat {{ addslashes($pej->nama) }} akan dihapus permanen.', 'Hapus Pejabat')"
                      class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
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
  @endif
</div>

{{-- ══════════════════════════════════════════════════
     MODAL — Tambah / Edit Pejabat
══════════════════════════════════════════════════ --}}
<div id="pejabatModal" style="display:none"
  class="fixed inset-0 z-50 flex items-center justify-center p-4">

  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>

  {{-- Panel --}}
  <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

    {{-- Header modal --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex-shrink-0">
      <div>
        <h3 class="font-semibold text-base" id="modalTitle">Tambah Pejabat</h3>
        <p class="text-xs text-slate-400 mt-0.5">Isi data pejabat desa</p>
      </div>
      <button type="button" onclick="closeModal()"
        class="grid place-items-center w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
        <i class="ti ti-x text-base"></i>
      </button>
    </div>

    {{-- Form (scrollable body) --}}
    <form method="POST" id="pejabatForm" action="{{ route('admin.master.pejabat.store') }}"
          enctype="multipart/form-data" class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
      @csrf
      <div id="methodField"></div>

      {{-- ── Foto ── --}}
      <div class="px-6 py-5">
        <label class="block text-sm font-medium mb-3">Foto Pejabat</label>
        <div class="flex items-center gap-4">
          {{-- Preview avatar --}}
          <div class="relative flex-shrink-0">
            <div class="w-20 h-20 rounded-xl overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 grid place-items-center">
              <img id="fotoPreview" src="" alt="" class="w-full h-full object-cover hidden">
              <i id="fotoPlaceholder" class="ti ti-camera text-2xl text-slate-400"></i>
            </div>
            <button type="button" id="hapusFotoBtn" onclick="hapusFoto()" style="display:none"
              class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-rose-500 hover:bg-rose-600 text-white grid place-items-center transition-colors shadow">
              <i class="ti ti-x text-[10px]"></i>
            </button>
          </div>
          {{-- Upload area --}}
          <label for="fotoInput"
            class="flex-1 flex flex-col items-center justify-center gap-1.5 h-20 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 cursor-pointer
                   hover:border-brand-400 dark:hover:border-brand-500 hover:bg-brand-50/40 dark:hover:bg-brand-500/5 transition-all">
            <i class="ti ti-upload text-slate-400 text-lg"></i>
            <span class="text-xs text-slate-400 text-center leading-tight">
              Klik untuk pilih foto<br>
              <span class="text-[10px]">JPG, PNG, WEBP — maks. 2 MB</span>
            </span>
          </label>
          <input type="file" id="fotoInput" name="foto" accept="image/*" class="sr-only" onchange="previewFoto(this)">
          <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
        </div>
      </div>

      {{-- ── Data Utama ── --}}
      <div class="px-6 py-5 space-y-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Data Utama</p>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1.5">Jabatan <span class="text-rose-500">*</span></label>
            <select name="jabatan_id" id="f_jabatan" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('jabatan_id') ? 'border-rose-500' : '' }}">
              <option value="">— Pilih —</option>
              @foreach($jabatans as $jab)
                <option value="{{ $jab->id }}" {{ old('jabatan_id') == $jab->id ? 'selected' : '' }}>
                  {{ $jab->nama }}{{ $jab->singkatan ? ' ('.$jab->singkatan.')' : '' }}
                </option>
              @endforeach
            </select>
            @error('jabatan_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Periode <span class="text-rose-500">*</span></label>
            <select name="periode_id" id="f_periode" required
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('periode_id') ? 'border-rose-500' : '' }}">
              <option value="">— Pilih —</option>
              @foreach($periodes as $per)
                @php $aktif = is_null($per->selesai) || $per->selesai->isFuture(); @endphp
                <option value="{{ $per->id }}" {{ old('periode_id') == $per->id ? 'selected' : '' }}>
                  {{ $per->nama }}{{ $aktif ? ' ✓' : '' }}
                </option>
              @endforeach
            </select>
            @error('periode_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2 sm:col-span-1">
            <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" id="f_nama" required
              class="w-full px-3 h-9 rounded-lg border bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none {{ $errors->has('nama') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }}"
              value="{{ old('nama') }}" placeholder="Nama lengkap">
            @error('nama')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label class="block text-sm font-medium mb-1.5">NIP <span class="text-slate-400 font-normal">(opsional)</span></label>
            <input type="text" name="nip" id="f_nip"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('nip') }}" placeholder="198001012006041001" maxlength="30">
          </div>
        </div>
      </div>

      {{-- ── Data Pribadi ── --}}
      <div class="px-6 py-5 space-y-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Data Pribadi <span class="font-normal normal-case">(opsional)</span></p>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1.5">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="f_tempat_lahir"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('tempat_lahir') }}" placeholder="Kendari">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="f_tanggal_lahir"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('tanggal_lahir') }}">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Pendidikan Terakhir</label>
            <input type="text" name="pendidikan" id="f_pendidikan"
              class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
              value="{{ old('pendidikan') }}" placeholder="S1 Hukum">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">No. HP / WhatsApp</label>
            <div class="flex">
              <span class="inline-flex items-center px-3 h-9 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-400 text-sm">+62</span>
              <input type="text" name="no_hp" id="f_no_hp"
                class="flex-1 min-w-0 px-3 h-9 rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
                value="{{ old('no_hp') }}" placeholder="81234567890">
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Alamat</label>
          <textarea name="alamat" id="f_alamat" rows="2"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm resize-none outline-none focus:ring-2 focus:ring-brand-500"
            placeholder="Alamat tempat tinggal…">{{ old('alamat') }}</textarea>
        </div>
      </div>

      {{-- ── Footer modal ── --}}
      <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50 dark:bg-slate-800/50 flex-shrink-0">
        <button type="button" onclick="closeModal()"
          class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit"
          class="flex items-center gap-2 px-5 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-device-floppy text-base" id="btnIcon"></i>
          <span id="btnLabel">Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
const STORE_URL   = '{{ route('admin.master.pejabat.store') }}';
const UPDATE_BASE = '{{ url('admin/master/pejabat') }}/';

/* ── Modal open / close ── */
function openModal() {
  resetForm();
  document.getElementById('pejabatModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  document.getElementById('pejabatModal').style.display = 'none';
  document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
});

/* ── Foto preview ── */
function previewFoto(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('fotoPreview').src = e.target.result;
    document.getElementById('fotoPreview').classList.remove('hidden');
    document.getElementById('fotoPlaceholder').classList.add('hidden');
    document.getElementById('hapusFotoBtn').style.display = '';
    document.getElementById('hapusFotoInput').value = '0';
  };
  reader.readAsDataURL(file);
}

function hapusFoto() {
  document.getElementById('fotoInput').value       = '';
  document.getElementById('fotoPreview').src       = '';
  document.getElementById('fotoPreview').classList.add('hidden');
  document.getElementById('fotoPlaceholder').classList.remove('hidden');
  document.getElementById('hapusFotoBtn').style.display = 'none';
  document.getElementById('hapusFotoInput').value  = '1';
}

/* ── Buka modal edit ── */
function openEdit(id, jabatanId, periodeId, nama, nip, tempatLahir, tglLahir, pendidikan, noHp, alamat, fotoUrl) {
  document.getElementById('modalTitle').textContent    = 'Edit Pejabat';
  document.getElementById('f_jabatan').value           = jabatanId;
  document.getElementById('f_periode').value           = periodeId;
  document.getElementById('f_nama').value              = nama;
  document.getElementById('f_nip').value               = nip;
  document.getElementById('f_tempat_lahir').value      = tempatLahir;
  document.getElementById('f_tanggal_lahir').value     = tglLahir;
  document.getElementById('f_pendidikan').value        = pendidikan;
  document.getElementById('f_no_hp').value             = noHp;
  document.getElementById('f_alamat').value            = alamat;

  const preview     = document.getElementById('fotoPreview');
  const placeholder = document.getElementById('fotoPlaceholder');
  const hapusBtn    = document.getElementById('hapusFotoBtn');
  if (fotoUrl) {
    preview.src = fotoUrl;
    preview.classList.remove('hidden');
    placeholder.classList.add('hidden');
    hapusBtn.style.display = '';
  } else {
    preview.src = '';
    preview.classList.add('hidden');
    placeholder.classList.remove('hidden');
    hapusBtn.style.display = 'none';
  }
  document.getElementById('fotoInput').value       = '';
  document.getElementById('hapusFotoInput').value  = '0';
  document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('pejabatForm').action    = UPDATE_BASE + id;
  document.getElementById('btnIcon').className     = 'ti ti-device-floppy text-base';
  document.getElementById('btnLabel').textContent  = 'Simpan Perubahan';

  document.getElementById('pejabatModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

/* ── Reset form ke mode tambah ── */
function resetForm() {
  document.getElementById('modalTitle').textContent    = 'Tambah Pejabat';
  document.getElementById('pejabatForm').reset();
  document.getElementById('pejabatForm').action        = STORE_URL;
  document.getElementById('methodField').innerHTML     = '';
  document.getElementById('btnIcon').className         = 'ti ti-plus text-base';
  document.getElementById('btnLabel').textContent      = 'Simpan';
  document.getElementById('fotoPreview').src           = '';
  document.getElementById('fotoPreview').classList.add('hidden');
  document.getElementById('fotoPlaceholder').classList.remove('hidden');
  document.getElementById('hapusFotoBtn').style.display = 'none';
  document.getElementById('hapusFotoInput').value      = '0';
}

{{-- Buka ulang modal jika ada error validasi --}}
@if($errors->any())
  document.addEventListener('DOMContentLoaded', () => openModal());
@endif
</script>
@endsection
